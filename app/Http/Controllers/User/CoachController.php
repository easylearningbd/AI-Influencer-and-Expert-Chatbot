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

class CoachController extends Controller
{

    protected $coachService;  

    public function __construct(CoachService $coachService ){

        $this->coachService = $coachService;  
    } 

    public function AllCoaches(){

        $user = Auth::user();
        $coaches = Coach::active()->get();

        foreach($coaches as $coach){
            $profile = UserCoachProfile::where('user_id',$user->id)
                        ->where('coach_id',$coach->id)
                        ->first();

          $coach->user_has_profile = $profile && $profile->onboarding_completed;
          $coach->needs_onboarding = !$coach->user_has_profile;
                        
        }

    return view('client.backend.coaches.all_coaches',compact('coaches'));

    }
    // End Method 

    public function CoachesShow(Coach $coach){

      $user = Auth::user();

        // Check if user has completed onboarding 
      $profile = UserCoachProfile::where('user_id',$user->id)
                        ->where('coach_id',$coach->id)
                        ->first();

      $needsOnboarding = !$profile || !$profile->onboarding_completed;

      /// Get user goals with the coach 
      $goals = $user->goals()
            ->where('coach_id', $coach->id)
            ->orderBy('created_at','desc')
            ->get();

      /// Get active session if exists
      $activeSession = $user->coachSessions()
        ->where('coach_id',$coach->id)
        ->where('is_active', true)
        ->first();

    // check if this will be first session 
    $isFirstSession = !$user->coachSessions()
        ->where('coach_id',$coach->id)
        ->exists();
    
    return view('client.backend.coaches.show_coaches',compact('profile','needsOnboarding','goals','activeSession','isFirstSession','coach'));

    }
     // End Method 

     public function CoachesOnborarding(Coach $coach){

      $user = Auth::user();

        // Check if user has completed onboarding 
      $profile = UserCoachProfile::where('user_id',$user->id)
                        ->where('coach_id',$coach->id)
                        ->first();

     if ($profile && $profile->onboarding_completed) {
        return redirect()->route('coaches.show',$coach->slug)
            ->with('info','You have already completed onboarding with this coach');
     }

     $questions = $coach->onboarding_questions ?? [];

     return view('client.backend.coaches.onboarding_coaches',compact('coach','profile','questions'));

     }
     // End Method 



}
