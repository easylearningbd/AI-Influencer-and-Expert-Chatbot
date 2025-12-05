<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;
use App\Models\Transaction;
use Illuminate\Support\Str; 

class PaymentController extends Controller
{
    public function UserPlans(){

        $user = Auth::user();
        $plans = Plan::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('price')
                ->get();

        /// Get user active subscription 
        $activeSubscription = $user->activeSubscription();

        return view('client.backend.payment.plans_index',compact('plans','activeSubscription'));
    }
    // End Method 

    public function UserTransactions(){

        $transactions = Transaction::where('user_id', Auth::id())
                ->where('status','pending')
                ->with(['plan'])
                ->orderBy('created_at','desc')
                ->paginate(10);

        return view('client.backend.payment.transactions_page',compact('transactions'));

    }
     // End Method 

     public function UserTransactionsShow(Transaction $transaction){

        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        $transaction->load(['plan']);

        return view('client.backend.payment.transaction_show',compact('transaction'));

     }
      // End Method 





} 
