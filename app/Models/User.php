<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

   

  /// Relationship 
  
    public function chats() : HasMany {
        return $this->hasMany(Chat::class);
   }

    public function transactions() : HasMany {
        return $this->hasMany(Transaction::class);
   }

    public function subscriptions() : HasMany {
        return $this->hasMany(Subscription::class);
   }

   /// Get user active subscription data 
   public function activeSubscription(){

    return $this->subscriptions()
            ->where('status','active')
            ->where(function ($query){
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })->first();

   }

   // Check if user has active subscription 
   public function hasActiveSubscription(): bool {
        return $this->activeSubscription() !== null;
   }


   /// Helper method for manage the token 

   public function hasEnoughTokens(int $tokensRequired = 5): bool {

        $subscription = $this->activeSubscription();
        if ($subscription && $subscription->canUseTokens($tokensRequired)) {
            return true;
        }
        return $this->token_balance >= $tokensRequired;

   }

   public function deductTokens(int $tokens = 5): void {

    $subscription = $this->activeSubscription();
     if ($subscription && $subscription->canUseTokens($tokens)) {
            $subscription->deductTokens($tokens);
            return;
        }

        $this->decrement('token_balance',$tokens);

   }

   public function addTokens(int $tokens) : void {
        $this->increment('token_balance',$tokens);
   }
 

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
