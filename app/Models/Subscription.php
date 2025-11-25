<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
     protected $guarded = [];

     protected $casts = [  
        'current_period_start' => 'datetime', 
        'current_period_end' => 'datetime', 
        'next_billing_date' => 'datetime',
        'cancelled_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

     public function plan(){
        return $this->belongsTo(Plan::class);
    }

    // Check if subscription is active 
    public function isActive(): bool {
        return $this->status === 'active' && (!$this->expires_at || $this->expires_at->isFuture());
    }

    /// check if the subscription is cancelled but still acive 

    public function isCancelled(): bool {
        return $this->status === 'cancelled' && $this->isActive();
    }

    /// Check if subscription has expired 

    public function isExpired(): bool {
        return $this->status  === 'expired' || ($this->expires_at && $this->expires_at->isPast());
    }

    // check the cancel subscription (will remain active untill period end )
    public function cancel(){
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'expires_at' =>  $this->current_period_end,
        ]);
    }

    /// Resume a cancelled subscription 
    public function resume() {
        if ($this->isCancelled()) {
            $this->update([
            'status' => 'active',
            'cancelled_at' => null,
            'expires_at' =>  null,
        ]);
        }
    }


    /// check if user can able to use tokens 

    public function canUseTokens(int $tokensRequired): bool {
        return $this->isActive() &&  $this->tokens_remaining >= $tokensRequired;
    }

    // Deduct tokens from subscription 

    public function deductTokens(int $tokens): bool {
        if (!$this->canUseTokens($tokens)) {
           return false;
        }

        $this->tokens_used_this_month += $tokens;
        $this->tokens_remaining -= $tokens;
        $this->save();

        return true;

    }

    /// Reset monthly tokens 
    public function resetMonthlyTokens(){
        $this->update([
            'tokens_used_this_month' => 0,
            'tokens_remaining' => $this->monthly_tokens,
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
            'next_billing_date' => now()->addMonth(),
        ]);
    }

    // check if subscriptio need renewal 

    public function needsRenewal(): bool {
        return $this->isActive() &&
        $this->current_period_end && 
        $this->current_period_end->isPast();
    }
 


}
