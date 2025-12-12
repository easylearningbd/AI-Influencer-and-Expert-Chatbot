<?php

namespace App\Services;
use App\Models\Coach;
use App\Models\User;
use App\Models\CoachSession;
use App\Models\CoachMessage;
use App\Models\UserCoachProfile;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Http; 

class CoachService
{

    /// Start a new session with coaches 

    public function startSession(User $user, Coach $coach) : CoachSession {

        // check if this is the first session 
        $isFirstSession = !CoachSession::where('user_id',$user->id)
                ->where('coach_id', $coach->id)
                ->exists();

        // Calculate tokens to charge
        $tokensToCharge = $isFirstSession ? $coach->session_start_tokens : 0;

        // Change tokens if needed
        if ($tokensToCharge > 0) {
           if ($user->token_balance <  $tokensToCharge) {
             throw new \Exception('Insufficient tokens. You need tokens to start this session');
           }
           $user->decrement('token_balance',$tokensToCharge);
        }

        // Create new Session 
        $session = CoachSession::create([
            'user_id' => $user->id,
            'coach_id' => $coach->id,
            'session_id' => Str::uuid(),
            'started_at' => now(),
            'last_activity_at' => now(),
            'is_first_session' => $isFirstSession,
            'tokens_charged' => $tokensToCharge,
            'was_charged' => $tokensToCharge > 0,
            'is_active' => true,
        ]);

        $coach->incrementSessions();

        return $session;
    }


    /// Send a message to the AI coach and get response 
public function sendMessage(CoachSession $session, string $message) : array {

    $user =  $session->user;
    $coach = $session->coach;
    $profile = UserCoachProfile::where('user_id',$user->id)
                ->where('coach_id',$coach->id)
                ->first();

    // Build personalized system prompt 
    $systemPrompt = $this->buildSystemPrompt($coach,$profile);

    // Get conversational history 
    $conversationHistory = $this->getConversationHistory($session, 20);

    /// Call Chatgpt API 
    $response = $this->callOpenAI($systemPrompt,$conversationHistory,$message);

    /// uPDATE SESSION METRICS 
    $session->incrementMessages();
    $session->increment('total_tokens_used', $response['tokens_used'] ?? 0);

    return [
        'message' => $response['message'],
        'tokens_used' => $response['tokens_used'] ?? 0,
    ];
}
// End sendMessage Method 

// Build personalized system prompt based on user profile. 

private function buildSystemPrompt(Coach $coach,  ?UserCoachProfile $profile) : string {

    $basePrompt = $coach->system_prompt ?? $this->getDefaultPrompt($coach->speciality);

    if (!$profile) {
        return $basePrompt;
    }

    $personalizedContext = "\n\nUser Profile:\n";

switch ($coach->specialty) {
    case 'career':
        if ($profile->current_role) {
            $personalizedContext .= "- Current Role: {$profile->current_role}\n";
        }
        if ($profile->target_role) {
            $personalizedContext .= "- Target Role: {$profile->target_role}\n";
        }
        if ($profile->years_experience) {
            $personalizedContext .= "- Years of Experience: {$profile->years_experience}\n";
        }
        if ($profile->skills) {
            $personalizedContext .= "- Skills: " . implode(', ', $profile->skills) . "\n";
        }
        if ($profile->career_aspirations) {
            $personalizedContext .= "- Career Aspirations: {$profile->career_aspirations}\n";
        }
        break;

    case 'fitness':
        if ($profile->fitness_level) {
            $personalizedContext .= "- Fitness Level: {$profile->fitness_level}\n";
        }
        if ($profile->height && $profile->weight) {
            $bmi = $profile->weight / (($profile->height / 100) ** 2);
            $personalizedContext .= "- Height: {$profile->height}cm, Weight: {$profile->weight}kg (BMI: " . number_format($bmi, 1) . ")\n";
        }
        if ($profile->gender) {
            $personalizedContext .= "- Gender: {$profile->gender}\n";
        }
        if ($profile->age) {
            $personalizedContext .= "- Age: {$profile->age}\n";
        }
        if ($profile->health_conditions) {
            $personalizedContext .= "- Health Conditions: " . implode(', ', $profile->health_conditions) . "\n";
        }
        if ($profile->fitness_goals) {
            $personalizedContext .= "- Fitness Goals: {$profile->fitness_goals}\n";
        }
        if ($profile->workout_preferences) {
            $personalizedContext .= "- Workout Preferences: " . implode(', ', $profile->workout_preferences) . "\n";
        }
        break;

    case 'finance':
        if ($profile->monthly_income) {
            $personalizedContext .= "- Monthly Income: $" . number_format($profile->monthly_income, 2) . "\n";
        }
        if ($profile->monthly_expenses) {
            $personalizedContext .= "- Monthly Expenses: $" . number_format($profile->monthly_expenses, 2) . "\n";
        }
        if ($profile->savings) {
            $personalizedContext .= "- Current Savings: $" . number_format($profile->savings, 2) . "\n";
        }
        if ($profile->debt) {
            $personalizedContext .= "- Current Debt: $" . number_format($profile->debt, 2) . "\n";
        }
        if ($profile->risk_tolerance) {
            $personalizedContext .= "- Risk Tolerance: {$profile->risk_tolerance}\n";
        }
        if ($profile->financial_goals) {
            $personalizedContext .= "- Financial Goals: " . implode(', ', $profile->financial_goals) . "\n";
        }
        if ($profile->investment_experience) {
            $personalizedContext .= "- Investment Experience: {$profile->investment_experience}\n";
        }
        break;

    case 'nutrition':
        if ($profile->dietary_restrictions) {
            $personalizedContext .= "- Dietary Restrictions: " . implode(', ', $profile->dietary_restrictions) . "\n";
        }
        if ($profile->food_allergies) {
            $personalizedContext .= "- Food Allergies: " . implode(', ', $profile->food_allergies) . "\n";
        }
        if ($profile->liked_foods) {
            $personalizedContext .= "- Liked Foods: " . implode(', ', $profile->liked_foods) . "\n";
        }
        if ($profile->disliked_foods) {
            $personalizedContext .= "- Disliked Foods: " . implode(', ', $profile->disliked_foods) . "\n";
        }
        if ($profile->daily_calorie_goal) {
            $personalizedContext .= "- Daily Calorie Goal: {$profile->daily_calorie_goal} kcal\n";
        }
        if ($profile->meal_preferences) {
            $personalizedContext .= "- Meal Preferences: " . implode(', ', $profile->meal_preferences) . "\n";
        }
        break;
}

    return $basePrompt . $personalizedContext; 

}

// End buildSystemPrompt Method 

// Get default system prompt based on speciality 
private function getDefaultPrompt(string $speciality): string {

    $prompts = [
            'career' => "You are Dr. Sarah Johnson, a professional career coach with an MBA and 15 years of experience. You specialize in leadership development, career transitions, salary negotiation, and interview preparation. Provide actionable, personalized career advice. Be supportive yet direct. Focus on practical steps the user can take immediately.",

            'fitness' => "You are Mike Thompson, a certified personal trainer (CPT) with 10 years of experience. You specialize in weight loss, muscle building, HIIT training, and nutrition. Provide safe, effective workout and nutrition advice. Always consider the user's fitness level and any health conditions. Be motivating and encouraging.",

            'finance' => "You are David Chen, a Certified Financial Planner (CFP) with 12 years of experience. You specialize in investment planning, retirement planning, debt management, and budgeting. Provide clear, practical financial advice. Consider the user's risk tolerance and financial goals. Be honest about risks and realistic about returns.",

            'nutrition' => "You are Chef Maria Rodriguez, a culinary institute graduate with 20 years of experience. You specialize in meal planning, special diets, nutrition, and quick recipes. Provide delicious, healthy meal suggestions that respect dietary restrictions and allergies. Be creative and make healthy eating enjoyable.",
        ];

}

// End getDefaultPrompt Method 

// get converstion historyfor context
 private function getConversationHistory(CoachSession $session, int $limit = 20) : array {

    $messages = CoachMessage::where('session_id',$session->id)
        ->orderBy('created_at','desc')
        ->limit($limit)
        ->get()
        ->reverse()
        ->map(function($msg){
            return [
                'role' => $msg->role,
                'content' => $msg->content
            ];
        })->toArray();

        return $messages;

 }
 // End getConversationHistory Method 

  
 // Call chatgpt api 
 private function callOpenAI(string $systemPrompt, array $conversationHistory, string $userMessage ) : array {

    $apiKey = config('services.openai.api_key');

    if (!$apiKey) {
        throw new \Exception('Open AI API key not configured');
    }

    // Build messages array 
    $messages =  [
        ['role' => 'system', 'content' => $systemPrompt]
    ];

    // Add Conversation history 
    foreach($conversationHistory as $msg) {
        $messages[] = $msg;
    }

    // Add current user message 
    $messages[] = ['role' => 'user', 'content' => $userMessage];

    try {

       $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post('https://api.openai.com/v1/chat/completions',[
                'model' => config('services.openai.model', 'gpt-4'),
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 1000, 
            ]);

      if ($response->failed()) {
        throw new \Exception('Open AI API request failed');
      }

      $data = $response->json();

      return [ 
        'message' => $data['choices'][0]['message']['content'] ?? 'No response generated',
        'tokens_used' => $data['usage']['total_tokens'] ?? 0,
      ]; 

    } catch (\Exception $e) {
       throw new \Exception('Failed to get AI Response' . $e->getMessage());
    } 
 }

  // End callOpenAI Method 

  // end coaching session 
  public function endSession(CoachSession $session, ?string $summary = null) : void {
    $session->endSession();

    if ($summary) {
        $session->update(['session_summary' => $summary]);
    }
  }
 
    // end endSession Method 

    public function needsOnboarding(User $user, Coach $coach): bool {

        $profile = UserCoachProfile::where('user_id',$use->id)
                ->where('coach_id',$coach->id)
                ->first();

        return !$profile || $profile->needsOnboarding();

    }
     // end needsOnboarding Method 

    public function completeOnboarding(User $user, Coach $coach, array $data) : UserCoachProfile {

        $profile = UserCoachProfile::updateOrCreate([
            'user_id' => $user->id,
            'coach_id' => $coach->id,
        ],
        array_merge($data,[
            'onboarding_completed' =>  true,
            'onboarding_completed_at' => now(),
            'onboarding_answers' => $data,
        ])       
        
        );

        return $profile; 
    } 
       // end completeOnboarding Method  
    
    
}
