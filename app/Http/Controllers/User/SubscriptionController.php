<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;
use App\Models\Transaction;
use Illuminate\Support\Str; 

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
        



    }
    // End Method 



    


} 
