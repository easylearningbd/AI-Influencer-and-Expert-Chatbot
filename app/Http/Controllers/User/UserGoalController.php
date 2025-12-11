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

class UserGoalController extends Controller
{
    public function CoachesGoalsIndex(Coach $coach){

        $user = Auth::user();
        $goals = UserGoal::where('user_id',$user->id)
                    ->where('coach_id',$coach->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Group goals by status 
        $inProgress = $goals->where('status','in_progress');
        $completed = $goals->where('status','completed');
        $paused = $goals->where('status','paused');

        return view('client.backend.goals.user_goals',compact('goals','inProgress','completed','paused','coach'));

    }
    // End Method 


}
 