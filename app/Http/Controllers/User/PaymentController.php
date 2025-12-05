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

    public function UserTokenBalance(){

        $user = Auth::user();

        // Get recent token transactions 
        $recentTransactions = Transaction::where('user_id',$user->id)
                ->where('status','completed')
                ->orderBy('approved_at','desc')
                ->limit(10)
                ->get();

        // Get token usage from chats (for per message it should be as 5)
        $totalMessages = $user->chats()->count();
        $tokensUsed = $totalMessages * 5;

        return view('client.backend.payment.token_balance',compact('user','recentTransactions','totalMessages','tokensUsed'));

    }
    // End Method 





} 
