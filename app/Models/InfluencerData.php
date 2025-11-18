<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InfluencerData extends Model
{
     protected $guarded = [];

     protected $casts = [  
        'chunk_size' => 'integer', 
    ];
 
    /// Relationship 
   public function influencerData() : BelongsTo {
        return $this->belongsTo(Influencer::class);
   }



}
