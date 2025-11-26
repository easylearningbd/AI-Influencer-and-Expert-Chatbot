<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Influencer extends Model
{
     protected $guarded = [];

      protected $casts = [
        'is_active' => 'boolean',
        'can_generate_image' => 'boolean', 
        'chat_count' => 'integer', 
    ];

   /// Auto Generate slug from name 
   protected static function boot(){
    parent::boot();
    static::creating(function($influencer){
        if (empty($influencer->slug)) {
           $influencer->slug = Str::slug($influencer->name);
        }
    });
   }

   /// Relationship 
   public function influencerData() : HasMany {
        return $this->hasMany(InfluencerData::class);
   }

    public function chats() : HasMany {
        return $this->hasMany(Chat::class);
   }

   // Helper method to get all training content 
   public function getAllTrainingContent(): string {
        return $this->InfluencerData()
            ->pluck('content')
            ->implode("\n\n");
   } 

   /// Helper Method to increment chat count 
   public function incrementChatCount(): void {
        $this->increment('chat_count');
   }
    




}
