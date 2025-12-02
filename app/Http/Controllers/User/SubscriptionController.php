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



    


} 
