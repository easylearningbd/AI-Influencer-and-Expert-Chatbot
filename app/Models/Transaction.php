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


     /// Relationship 
   public function user() : BelongsTo {
        return $this->belongsTo(User::class);
   }


    /// Relationship 
   public function plan() : BelongsTo {
        return $this->belongsTo(Plan::class);
   }



}
