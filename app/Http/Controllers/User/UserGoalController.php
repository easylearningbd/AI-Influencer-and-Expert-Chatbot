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

    public function CoachesGoalsProgress(Request $request,Coach $coach,UserGoal $goal ){

        $validated = $request->validate([
            'progress_percentage' => 'required|integer'
        ]);

        // Verify goal belogns to user and coach 
        if ($goal->user_id !== Auth::id() || $goal->coach_id !== $coach->id ) {
            abort(403,'Unauthorized');
        }

        try {
            
            $goal->updateProgress($validated['progress_percentage']);

       if ($request->expectsJson()) {
           return response()->json([
                'success' =>  true,
                'message' => 'Progress Updated Successully',
                'goal' => $goal->fresh()
           ]);
        }

        $notification = array(
            'message' => 'Progress Updated Successully',
            'alert-type' => 'success'
             ); 

        return redirect()->back()->with($notification);  

        } catch (\Exception $e) {
            return back()->with('error','Failed to update progress' . $e->getMessage());
        }

    }
      // End Method

    public function CoachesGoalsUpdate(Request $request,Coach $coach,UserGoal $goal ){

        $validated = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high',
            'target_date' => 'nullable|date', 
            'status' => 'sometimes|in:in_progress,completed,paused',
            'coach_notes' => 'nullable|string'
        ]);

        // Verify goal belogns to user and coach 
        if ($goal->user_id !== Auth::id() || $goal->coach_id !== $coach->id ) {
            abort(403,'Unauthorized');
        }


        try {
            $goal->update($validated);

       if ($request->expectsJson()) {
           return response()->json([
                'success' =>  true,
                'message' => 'Goal Updated Successully',
                'goal' => $goal->fresh()
           ]);
        }

        $notification = array(
            'message' => 'Goal Updated Successully',
            'alert-type' => 'success'
             ); 

        return redirect()->back()->with($notification);   

        } catch (\Exception $e) {
            return back()->with('error','Failed to update progress' . $e->getMessage());
        } 

    }
       // End Method

    public function CoachesGoalsDelete(Coach $coach,UserGoal $goal){

         // Verify goal belogns to user and coach 
        if ($goal->user_id !== Auth::id() || $goal->coach_id !== $coach->id ) {
            abort(403,'Unauthorized');
        }

       try {
            $goal->delete();

       if ($request->expectsJson()) {
           return response()->json([
                'success' =>  true,
                'message' => 'Goal Deleted Successully', 
           ]);
        }

        $notification = array(
            'message' => 'Goal Deleted Successully',
            'alert-type' => 'success'
             ); 

        return redirect()->back()->with($notification);   

        } catch (\Exception $e) {
            return back()->with('error','Failed to update progress' . $e->getMessage());
        } 

    }
     // End Method


}
 