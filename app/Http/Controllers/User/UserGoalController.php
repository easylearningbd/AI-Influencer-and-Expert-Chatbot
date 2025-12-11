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

    public function CoachesGoalsStore(Request $request, Coach $coach){

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high',
            'target_date' => 'nullable|date|after:today', 
        ]);

        $user = Auth::user();
        $goal = UserGoal::create([
            'user_id' => $user->id,
            'coach_id' => $coach->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'target_date' => $validated['target_date'] ?? null,
            'status' => 'in_progress',
            'started_at' => now(),
            'progress_percentage' => 0
        ]);

        if ($request->expectsJson()) {
           return response()->json([
                'success' =>  true,
                'message' => 'Goal Created Successully',
                'goal' => $goal
           ]);
        }

        $notification = array(
            'message' => 'Goal Created Successully',
            'alert-type' => 'success'
             ); 

        return redirect()->route('coaches.goals.index',$coach->slug)->with($notification); 

    }
    // End Method 


}
 