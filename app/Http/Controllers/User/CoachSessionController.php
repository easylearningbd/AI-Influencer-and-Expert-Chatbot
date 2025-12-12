<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoachSession;
use App\Models\CoachMessage;
use App\Models\Coach;
use App\Models\UserCoachProfile;
use App\Services\CoachService;
use Illuminate\Support\Facades\Auth;
use App\Models\UserGoal;

class CoachSessionController extends Controller
{
    protected $coachService;

    public function __construct(CoachService $coachService){
        $this->coachService = $coachService;
    }


    public function CoachesSessionStart(Request $request,Coach $coach){

        $user = Auth::user();

        try {
            
            // Check if user needs onboarding 
            if ($this->coachService->needsOnboarding($user,$coach)) {
                return response()->json([
                    'error' => 'Please complete onboarding first',
                    'redirect' => route('coaches.onborarding',$coach->slug)
                ],403);
            }
            
            // Check existing active session
        $existingSession = CoachSession::where('user_id',$user->id)
                        ->where('coach_id',$coach->id)
                        ->where('is_active',true)
                        ->first();

        if ($existingSession) {
            return response()->json([
                'session' => $existingSession,
                'message' => 'Resumed existing session'
            ]);
        }

        // Start new session
        $session = $this->coachService->startSession($user,$coach);

        return response()->json([
            'success' => true,
            'session' => $session,
            'message' => $session->was_charged
            ? "Session started {$session->tokens_charged} tokens charged."
            : "Session started no charge for returing users",
            'tokens_remaining' => $user->fresh()->token_balance
        ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
    //End Method 


}
