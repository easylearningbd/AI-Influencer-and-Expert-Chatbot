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

     public function CoachesOnborardingSubmit(Request $request , Coach $coach){

        $user = Auth::user();

        $rules = $this->getValidationRules($coach->speciality);
        $validated = $request->validate($rules);

        try {
            // save onboarding data 
        
            $this->coachService->completeOnboarding($user,$coach,$validated );

    $notification = array(
    'message' => 'Onboarding completed! you can now start your coaching session',
    'alert-type' => 'success'
        ); 

          return redirect()->route('coaches.show',$coach->slug)->with($notification); 
             
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error','Failed to complete on boarding:' . $e->getMessage());
        }


     }
          // End Method 

    private function getValidationRules(string $speciality): array {

        $commonRules = [];
        
        switch ($speciality) {
            case 'career':
                return array_merge($commonRules, [
                    'current_role' => 'nullable|string|max:255',
                    'target_role' => 'nullable|string|max:255',
                    'years_experience' => 'nullable|integer|min:0|max:100',
                    'skills' => 'nullable|array',
                    'career_aspirations' => 'nullable|string|max:1000',
                ]);

            case 'fitness':
                return array_merge($commonRules, [
                    'fitness_level' => 'nullable|string|in:beginner,intermediate,advanced',
                    'height' => 'nullable|numeric|min:50|max:300',
                    'weight' => 'nullable|numeric|min:20|max:500',
                    'gender' => 'nullable|string|in:male,female,other',
                    'age' => 'nullable|integer|min:13|max:120',
                    'health_conditions' => 'nullable|array',
                    'fitness_goals' => 'nullable|string|max:1000',
                    'workout_preferences' => 'nullable|array',
                ]);

            case 'finance':
                return array_merge($commonRules, [
                    'monthly_income' => 'nullable|numeric|min:0',
                    'monthly_expenses' => 'nullable|numeric|min:0',
                    'savings' => 'nullable|numeric|min:0',
                    'debt' => 'nullable|numeric|min:0',
                    'risk_tolerance' => 'nullable|string|in:conservative,moderate,aggressive',
                    'financial_goals' => 'nullable|array',
                    'investment_experience' => 'nullable|string|max:500',
                ]);

            case 'nutrition':
                return array_merge($commonRules, [
                    'dietary_restrictions' => 'nullable|array',
                    'food_allergies' => 'nullable|array',
                    'liked_foods' => 'nullable|array',
                    'disliked_foods' => 'nullable|array',
                    'daily_calorie_goal' => 'nullable|integer|min:500|max:10000',
                    'meal_preferences' => 'nullable|array',
                ]);

            default:
                return $commonRules;
        }



    }
     // End Method 



}
