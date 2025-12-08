<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachMessage extends Model
{
    protected $guarded = [];

      protected $casts = [
        'tokens_used' => 'integer', 
    ];



}
