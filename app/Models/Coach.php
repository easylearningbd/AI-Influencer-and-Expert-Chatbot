<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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


    public function profiles(): HasMany {

      return $this->hasMany(UserCoachProfile::class);

    }

     public function sessions(): HasMany {

      return $this->hasMany(CoachSession::class);
      
    }

     public function goals(): HasMany {

      return $this->hasMany(UserGoal::class);
      
    }

    public function incrementSessions(): void {
      $this->increment('total_sessions');
    }

    public function scopeActive($query) {
      return $query->where('is_active', true);
    }

    public function scopeBySpecialty($query, string $speciality){
      return $query->where('speciality',$speciality);
    } 

 
}
