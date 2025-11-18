<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
     protected $guarded = [];

     protected $casts = [  
        'tokens_used' => 'integer', 
    ];
 
    /// Relationship 
   public function user() : BelongsTo {
        return $this->belongsTo(User::class);
   }

   public function influencer() : BelongsTo {
        return $this->belongsTo(Influencer::class);
   }



} 
