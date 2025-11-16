<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Plan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'features' => 'array',
        'price' => 'decimal:2',
        'tokens' => 'integer',
        'sort_order' => 'integer', 
    ];

   /// Auto Generate slug from name 
   protected static function boot(){
    parent::boot();
    static::creating(function($plan){
        if (empty($plan->slug)) {
           $plan->slug = Str::slug($plan->name);
        }
    });
   }






}
