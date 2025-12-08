<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

     public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

     public function coach(): BelongsTo {
        return $this->belongsTo(Coach::class);
    }

    public function updateProgress(int $percentage) : void {
      $history = $this->progress_history ?? [];
      $history[] = ['percentage' => $percentage, 'updated_at' => now()->toDateTimeString()];
      $this->update([
        'progress_percentage' => $percentage,
        'progress_history' => $history
      ]);
      if($percentage >= 100 && $this->status !== 'completed') $this->markCompleted();
    }

    public function markCompleted(): void {
      $this->update([
        'status' => 'completed',
        'completed_at' =>  now(),
        'progress_percentage' => 100
      ]);
    }

    public function scopeInProgress($query) {
      return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query) {
      return $query->where('status','completed');
    }




}
