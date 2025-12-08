<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachSession extends Model
{
    protected $guarded = [];

      protected $casts = [
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'ended_at' => 'datetime', 
        'is_first_session' => 'boolean',
        'was_charged' => 'boolean', 
        'is_active' => 'boolean',
        'tokens_charged' => 'integer', 
        'total_messages' => 'integer',
        'total_tokens_used' => 'integer',
        'duration_minutes' => 'integer',
        'action_items' => 'array',
        'user_satisfaction' => 'decimal:2',
    ];



}
