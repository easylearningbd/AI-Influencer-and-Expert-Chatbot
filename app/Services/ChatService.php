<?php

namespace App\Services;
use App\Models\Influencer;
use App\Models\User;
use App\Models\Chat;
use OpenAI;

class ChatService
{
    protected $client;
    protected $model;
     
    public function __construct()
    {
        $apiKey = config('services.openai.api_key', env('OPENAI_API_KEY'));

        if (!$apiKey) {
           throw new \Exception('OpenAI API key is not configured. plz add your OPENAI_API_KEY to your .env file.');
        }

        $this->client = OpenAI::client($apiKey);
        $this->model = config('services.openai.model', env('OPENAI_MODEL','gpt-4-turbo-preview')); 

    }

    // Send a message to influencer AI and Get a response 

    public function chat(User $user, Influencer $influencer, string $message, ?string $sessionId = null, string $language = 'en'): array {

        // Check if user has enough tokens 
        $tokensRequired = (int) env('CHAT_TOKENS_PER_MESSAGE',5);

        if (!$user->hasEnoughTokens($tokensRequired)) {
            throw new \Exception('Insufficient tokens. please purchase more tokens to continue chatting');
        }

        // Get conversation history for context
        $conversationHistory = $this->getConversationHistory($user,$influencer,$sessionId);

        $systemPrompt = $this->buildSystemPrompt($influencer,$message,$language);

        /// Messages for GPT 
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        // add conversation history 
        foreach($conversationHistory as $chat){
            $messages[] = ['role' => 'user', 'content' => $chat->message];
            $messages[] = ['role' => 'assistant', 'content' => $chat->response];
        }

        // add current message 

        $messages[] = ['role' => 'user', 'content' => $message];

        try {

            // Call OpenAI API 
            $response = $this->client->chat()->create([
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => 800,
                'temperature' => 0.9,
                'presence_penalty' => 0.3,
                'frequency_penalty' => 0.3,
            ]);

            $aiResponse = $response->choices[0]->message->content;

            // Generate session id if not provided 
            if (!$sessionId) {
               $sessionId = uniqid('session_', true);
            }

            // save data to chat table 
            $chat = Chat::create([
                'user_id' => $user->id,
                'influencer_id' => $influencer->id,
                'message' => $message,
                'response' => $aiResponse,
                'language' => $language,
                'tokens_used' => $tokensRequired,
                'session_id' => $sessionId
            ]);

            /// Deduct tokens from user 
            $user->deductTokens($tokensRequired);

            // Increatement influencer table chat count fied
            $influencer->incrementChatCount();

            return [
                'success' => true,
                'response' => $aiResponse,
                'chat_id' => $chat->id,
                'session_id' => $sessionId,
                'tokens_used' => $tokensRequired,
                'tokens_remaining' => $user->fresh()->token_balance, 
            ];

        } catch (\Exception $e) {
           throw new \Exception('Error communicating with AI: ' . $e->getMessage());
        } 

    }
    /// End chat Method 

    protected function buildSystemPrompt(Influencer $influencer, string $userMessage = '', string $language = 'en'): string 
    {
        // Use custom system prompt if available, or it will be build one
        $basePrompt = $influencer->system_prompt ?: $this->generateBasePrompt($influencer);

        $relevantContext = $this->getRelevantContext($influencer,$userMessage);

        /// Combine base prompt with relevant context
        $fullPrompt = $basePrompt;

        if ($relevantContext) {
           $fullPrompt .= "\n\n== RELEVANT KNOWLEDGE FROM { $influencer->name }'s CONTENT ===\n";
           $fullPrompt .= $relevantContext;
           $fullPrompt .= "\n=== END OF KNOWLEDGE BASE ===\n\n"; 
        }

        $fullPrompt .= "\n\nIMPORTANT INSTRUCTIONS:\n";
        $fullPrompt .= "1. You ARE {$influencer->name}. Not an AI pretending to be them.\n";
        $fullPrompt .= "2. Use THEIR exact words, phrases, and expressions from the knowledge base above.\n";
        $fullPrompt .= "3. Match THEIR tone, energy, and communication style perfectly.\n";
        $fullPrompt .= "4. Reference THEIR specific experiences, stories, and examples from the knowledge base.\n";
        $fullPrompt .= "5. Stay 100% in character. Never break character or mention you're an AI.\n";
        $fullPrompt .= "6. Answer based PRIMARILY on the knowledge base provided. If the knowledge base doesn't cover the topic, respond as {$influencer->name} would based on their known personality and style.\n";

        // Add language instruction if not english
        if ( $language !== 'en') {
            $languageNames = $this->getLanguageNames();
            if (isset($languageNames[$language])) {
               $fullPrompt .= "7. RESPOND ENTIRELY IN {$languageNames[$language]}. Translate your response to {$languageNames[$language]} while maintaining your personality and style.\n";
            }
        }

        return $fullPrompt;

    }

     /// End buildSystemPrompt  Method 

     // Get language full names from language codes 
     protected function getLanguageNames(): array {
        return [
            'en' => 'English',
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
            'it' => 'Italian',
            'pt' => 'Portuguese',
            'ru' => 'Russian',
            'zh' => 'Chinese',
            'ja' => 'Japanese',
            'ko' => 'Korean',
            'ar' => 'Arabic',
            'hi' => 'Hindi',
            'bn' => 'Bengali',
            'nl' => 'Dutch',
            'tr' => 'Turkish',
            'pl' => 'Polish',
            'vi' => 'Vietnamese',
            'th' => 'Thai',
            'id' => 'Indonesian',
            'uk' => 'Ukrainian',
        ];
     }

     //  End getLanguageNames method 

     /// Generate base prompt for influencer 
     protected function generateBasePrompt(Influencer $influencer): string {

        $prompt = "You are {$influencer->name}";

        if ($influencer->niche) {
            $prompt .= ", a leading expert in {$influencer->niche}";
        }
        $prompt .= "\n\n";

        if ($influencer->bio) {
            $prompt .= "=== YOUR BACKGROUND ===\n{$influencer->bio}\n\n";
        }
        if ($influencer->style) {
            $prompt .= "=== YOUR COMMUNICATION STYLE ===\n{$influencer->style}\n\n";
        }

        return $prompt;

     }
      //  End generateBasePrompt method 


      protected function getRelevantContext(Influencer $influencer, string $userMessage): string {

        if (empty($userMessage)) {
            // if no specific message, retun a general overview 
            return $this->getTrainingContext($influencer,2000);
        }

        // get all training data from chunks
        $trainingData = $influencer->influencerData()->get();

        if ($trainingData->isEmpty()) {
            return '';
        }

        // Keyword based relevence scroing 
        $userKeyword = $this->extractkeywords($userMessage);
        $rankedChunks = [];

        foreach($trainingData as $data){
            $content = $data->content;
            $relevantScore = $this->calculateRelevanceScore($content,$userKeyword);

            if ($relevantScore > 0) {
                $rankedChunks[] = [
                    'content' => $content,
                    'score' => $relevantScore ,
                    'type' => $data->type,
                ];
            }
        }

        // sort by relevance score 
        usort($rankedChunks, function ($a, $b){
            return $b['score'] <=> $a['score'];
        });

        /// take top 3 most releavent chunks 
        $maxLength = (int) env('CHAT_MAX_CONTEXT_LENGTH',4000);
        $relevantContext = '';
        $currentLength = 0;

        foreach(array_slice($rankedChunks,0 ,3) as $chunk){
            $chunkLength = strlen($chunk['content']);

            if ($currentLength + $chunkLength > $maxLength) {
                $remainingSpace = $maxLength - $currentLength;
                if($remainingSpace > 200){
                    $relevantContext .= substr($chunk['content'], 0, $remainingSpace) . "...\n\n";
                }
                break;
            }

            $relevantContext .= $chunk['content'] . "\n\n";
            $currentLength += $chunkLength + 2;

        }
        return trim($relevantContext); 

      }
        //  End getRelevantContext method 

        // Extract important keywords from user message
    protected function extractKeywords(string $text): array
    {
        // Convert to lowercase and remove punctuation
        $text = strtolower(preg_replace('/[^\w\s]/', ' ', $text));

        // Common stop words to filter out
        $stopWords = ['the', 'is', 'at', 'which', 'on', 'a', 'an', 'and', 'or', 'but', 'in', 'with', 'to', 'for', 'of', 'as', 'by', 'from', 'be', 'are', 'was', 'were', 'been', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'should', 'could', 'can', 'may', 'might', 'must', 'i', 'you', 'he', 'she', 'it', 'we', 'they', 'what', 'who', 'when', 'where', 'why', 'how'];

        // Split into words
        $words = preg_split('/\s+/', $text);

        // Filter out stop words and short words
        $keywords = array_filter($words, function ($word) use ($stopWords) {
            return !in_array($word, $stopWords) && strlen($word) > 2;
        });

        return array_values(array_unique($keywords));
    }

    //  End extractKeywords method 


    // calculat relevance score between content and keywords
    protected function calculateRelevanceScore(string $content, array $keywords): int {

        $contentLower = strtolower($content);
        $score = 0;

        foreach($keywords as $keyword){
            // count exact word matches 
            $exactMatches = substr_count($contentLower, ' ' . $keyword . ' ');
            $score += $exactMatches  * 10;

            // Count partial matches 
            $partialMatches = substr_count($contentLower, $keyword);
            $score += $partialMatches * 3; 

        }

        $keywordString = implode('|',$keyword);
        if (preg_match_all("/$keywordString/i", $content) > count($keywords)) {
             $score += 20;
        }

        return $score;

    } 
       //  End calculateRelevanceScore method 








}
