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



}
