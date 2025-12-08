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



    }



    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
}
