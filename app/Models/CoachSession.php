<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

     public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

     public function coach(): BelongsTo {
        return $this->belongsTo(Coach::class);
    }

    public function incrementMessages(): void {
      $this->increment('total_messages');
      $this->update(['last_activity_at' => now()]);
    }

    public function endSession() : void {
      $this->update([
        'is_active' => false,
        'ended_at' => now(),
        'duration_minutes' => now()->diffInMinutes($this->started_at),
      ]);
    }

    public function scopeActive($query) {
      return $query->where('is_active', true);
    }

    public function scopeForUser($query, int $userId) {
      return $query->where('user_id',$userId);
    }



}
