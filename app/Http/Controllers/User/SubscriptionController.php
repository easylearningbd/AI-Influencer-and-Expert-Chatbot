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
 
        // For free plan, 
        if ($plan->price == 0) {

           if ($activeSubscription) {
            $activeSubscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'expires_at' => now(),
            ]);
         }

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

            return redirect()->route('user.subscription.manage')->with('success',$message);

        }

        // For te paid palns it will be redirect to Checkout page
        return redirect()->route('user.subscription.checkout',$plan->slug);

    }
    // End Method 

    public function SubscriptionCancel(Subscription $subscription){
        
        $user = Auth::user();

        if ($subscription->user_id !== $user->id ) {
           abort(403,'Unauthorized');
        }

        $subscription->cancel();

        return back()->with('success', 'Subscription cancelled. You can still use your tokens until' . $subscription->expires_at->format('M d, Y') . '.');
    }
    // End Method 

    public function SubscriptionCheckout(Plan $plan){

        $user = Auth::user();

        $activeSubscription = $user->activeSubscription();

        // Check if trying to check their current plan
        if ($activeSubscription && $activeSubscription->plan_id === $plan->id ) {
           return redireact()->route('user.subscription.manage')->with('info','You are already subscribed to this plan');
        }

        return view('client.backend.subscription.checkout',compact('plan','activeSubscription'));
    }
     // End Method 

    public function SubscriptionPayment(Request $request, Plan $plan){

        $request->validate([
            'payment_method' => 'required|in:bank_transfer',
            'bank_details' => 'required',
            'payment_proof' => 'required|file|mimes:png,jpg,jpeg,pdf|max:5120'
        ]);

        $user = Auth::user();

        $activeSubscription = $user->activeSubscription();
        $pendingSubscription = $user->pendingSubscription();

        if ($activeSubscription && $activeSubscription->plan_id === $plan->id) {
            return back()->with('info','You are already subscribed to this plan');
        }

        if ($pendingSubscription && $pendingSubscription->plan_id === $plan->id) {
            return back()->with('info','You are already have a pending subscrition for this plan. Please wait for admin approval');
        }

        // Cancel any pending subscriptions of other plans
        if ($pendingSubscription && $pendingSubscription->plan_id !== $plan->id) {
            $pendingSubscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'expires_at' => now(),
            ]);
        }


         if ($activeSubscription ) {
            $activeSubscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'expires_at' => now(),
            ]);
        }

        // Handle payment proof upload 
        $paymentProofPath = null;

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $fileName = time() . '_' . $user->id . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/payment_proofs';

            /// Create director if its doesn't exist
            if (!file_exists(public_path($filePath))) {
                mkdir(public_path($filePath), 0777, true);
            }

            $file->move(public_path($filePath), $fileName);
            $paymentProofPath  = $filePath . '/' . $fileName; 

        }

        // Create pending data store in subscription table 


        /// create nad store in transactions table
        
 


    }
    // End Method 



    


} 
