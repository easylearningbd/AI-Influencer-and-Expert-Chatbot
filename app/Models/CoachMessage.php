<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoachMessage extends Model
{
    protected $guarded = [];

      protected $casts = [
        'tokens_used' => 'integer', 
    ];

     public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

     public function coach(): BelongsTo {
        return $this->belongsTo(Coach::class);
    }

     public function session(): BelongsTo {
        return $this->belongsTo(CoachSession::class,'session_id');
    }



}
