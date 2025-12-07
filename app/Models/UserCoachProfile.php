<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    



}
