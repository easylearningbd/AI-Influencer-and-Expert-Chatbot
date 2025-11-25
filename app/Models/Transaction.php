<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $guarded = [];

     protected $casts = [  
        'amount' => 'decimal:2', 
        'tokens' => 'integer', 
        'approved_at' => 'datetime', 
    ];

    /// Auto Generate Transaction ID 
    protected static function boot(){
        parent::boot();

        static::creating(function ($transaction){
            if (empty($transaction->transaction_id)) {
                $transaction->transaction_id = 'TXN-' .strtoupper(uniqid()); 
            }
        });
    }


     /// Relationship 
   public function user() : BelongsTo {
        return $this->belongsTo(User::class);
   }


    /// Relationship 
   public function plan() : BelongsTo {
        return $this->belongsTo(Plan::class);
   }

   public function approvedBy(): BelongsTo {
        return $this->belongsTo(User::class, 'approved_by');
   }

   public function isPending(): bool {
        return $this->status === 'pending';
   }

   public function isCompleted(): bool {
        return $this->status === 'completed';
   }

   public function isRejected(): bool {
        return $this->status === 'rejected';
   }

   public function getStatusBadgeAttribute(): string {
     
    return match($this->status) {
        'pending' => '<span class="badge bg-warning">Pending</span>',
        'completed' => '<span class="badge bg-success">Completed</span>',
        'rejected' => '<span class="badge bg-danger">Rejected</span>',
        'refunded' => '<span class="badge bg-info">Refunded</span>',
        default => '<span class="badge bg-secondary">Unknown</span>',
    };

   }
 


}
