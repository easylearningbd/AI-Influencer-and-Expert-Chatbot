<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;
use App\Models\Transaction;
use Illuminate\Support\Str; 
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    

    public function UserSubscriptionManage(){

        $user = Auth::user();
        $activeSubscription = $user->activeSubscription();
        $allSubscritions = $user->subscriptions()->with('plan')->latest()->get();

        return view('client.backend.subscription.manage',compact('activeSubscription','allSubscritions'));

    }
    // End Method 

    public function SubscriptionSubscribe(Request $request, Plan $plan){

        $user = Auth::user();

        $activeSubscription = $user->activeSubscription();

        // Check if the user is trying to subscribe to their current plan
        if ($activeSubscription && $activeSubscription->plan_id === $plan->id) {
           return back()->with('Info', 'You are already subscribed to this plan');
        }

        // If the user has active subscription then cancel if first next to switching 

        if ($activeSubscription) {
            $activeSubscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'expires_at' => now(),
            ]);
        }

        // For free plan, 
        if ($plan->price == 0) {
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'monthly_tokens' => $plan->tokens,
                'tokens_used_this_month' => 0,
                'tokens_remaining' => $plan->tokens,
                'current_period_start' => now(),
                'current_period_end' => now()->addMonth(),
                'next_billing_date' => now()->addMonth(), 
            ]);

            $user->addTokens($plan->tokens);

            $message = $activeSubscription
                ? 'Plan switched successfully! You are now on the' . $plan->name . 'Plan with' . $plan->tokens . 'Tokens'
                : 'free subscription Activated Successfully you have ' .$plan->tokens . 'Tokens';

            return redirect('user.subscription.manage')->with('success',$message);

        }

        // For te paid palns it will be redirect to Checkout page
        return redirect()->route('user.subscription.checkout',$plan->sulg);

    }
    // End Method 

    public function SubscriptionCheckout(Plan $plan){

    }
     // End Method 



    


} 
