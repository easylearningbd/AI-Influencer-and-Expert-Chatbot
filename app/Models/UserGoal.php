<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGoal extends Model
{
    protected $guarded = [];

      protected $casts = [
        'target_date' => 'date', 
        'started_at' => 'date', 
        'completed_at' => 'date',
        'progress_percentage' => 'integer', 
        'milestones' => 'array',
        'completed_milestones' => 'array',
        'metrics' => 'array',
        'progress_history' => 'array', 
        'action_plan' => 'array',
    ];



}
