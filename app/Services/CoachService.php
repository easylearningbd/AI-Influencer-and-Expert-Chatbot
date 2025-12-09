<?php

namespace App\Services;
use App\Models\Coach;
use App\Models\User;
use App\Models\CoachSession;
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


private function buildSystemPrompt(Coach $coach,  ?UserCoachProfile $profile) : string {


}

// End buildSystemPrompt Method 




    
    
}
