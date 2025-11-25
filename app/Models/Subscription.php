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


}
