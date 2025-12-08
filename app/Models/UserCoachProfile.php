<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserCoachProfile extends Model
{
    protected $guarded = [];

     protected $casts = [  
        'profile_data' => 'array', 
        'skills' => 'array', 
        'health_conditions' => 'array',
        'workout_preferences' => 'array',
        'financial_goals' => 'array',

        'dietary_restrictions' => 'array',
        'food_allergies' => 'array',
        'liked_foods' => 'array',
        'disliked_foods' => 'array',
        'meal_preferences' => 'array',
        'onboarding_answers' => 'array',

        'onboarding_completed' => 'boolean',
        'onboarding_completed_at' => 'datetime',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',

        'monthly_income' => 'decimal:2',
        'monthly_expenses' => 'decimal:2',
        'savings' => 'decimal:2',
        'debt' => 'decimal:2',
        'age' => 'decimal:2',
        'years_experience' => 'integer',
        'daily_calorie_goal' => 'integer', 
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

     public function coach(): BelongsTo {
        return $this->belongsTo(Coach::class);
    }
    
    public function completeOnboarding() : void {
        $this->update(['onboarding_completed' => true, 'onboarding_completed_at' => now()]);
    }

    public function needsOnboarding(): bool {
        return !$this->onboarding_completed;
    }

    public function getProfileSummary(): string {

        $coach = $this->coach;
        $summary = [];

switch ($coach->speciality) {
    case 'carrer':
        if($this->current_role) $summary[] = "Current: {$this->current_role}";
        if($this->target_role) $summary[] = "Target: {$this->target_role}";
        break;

    case 'fitness':
        if($this->fitness_level) $summary[] = "Level: {$this->fitness_level}";
        if($this->fitness_goals) $summary[] = "Goal: {$this->fitness_goals}";
        break;
    case 'finance':
        if($this->monthly_income) $summary[] = "Income: $" . number_format($this->monthly_income,0) ;
        if($this->risk_tolerance) $summary[] = "Rick: {$this->risk_tolerance}";
        break;
    case 'nutrition':
        if($this->daily_calorie_goal) $summary[] = "{$this->daily_calorie_goal}kcal/day"; 
        break; 
        }
        return implode(' | ',$summary) ?: 'Profile Incomplete'; 

    }
    // end getProfileSummary method 



}
