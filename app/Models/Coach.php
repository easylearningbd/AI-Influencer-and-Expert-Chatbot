<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    protected $guarded = [];

      protected $casts = [
        'expertise' => 'array',
        'onboarding_questions' => 'array', 
        'achievements' => 'array',
        'subsequent_chats_free' => 'boolean',
        'is_active' => 'boolean',
        'session_start_tokens' => 'integer',
        'years_experience' => 'integer',
        'total_sessions' => 'integer', 
        'average_rating' => 'decimal:2', 
    ];
 
}
