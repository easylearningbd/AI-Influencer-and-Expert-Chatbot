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


    /////////////// All for Admin ///////

    public function AdminPaymentIndex(Request $request){

        $query = Transaction::with(['user','plan','approvedBy']);

        // Filter by Status 
        if ($request->filled('status')) {
            $query->where('status',$request->status);
        }

         // Filter by Payment method  
        if ($request->filled('payment_method')) {
            $query->where('payment_method',$request->payment_method);
        }

         // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=' ,$request->date_from);
        }

         if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=' ,$request->date_to);
        }

        /// Search by transaction id or user name 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                ->orWhereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $transaction = $query->orderBy('created_at','desc')->paginate(10);

$stats = [
    'pending' => Transaction::where('status','pending')->count(),
    'completed' => Transaction::where('status','completed')->count(),
    'rejected' => Transaction::where('status','rejected')->count(),
    'total_revenue' => Transaction::where('status','completed')->sum('amount'),
];

return view('admin.backend.payment.payment_index',compact('transaction','stats')); 

    }
     // End Method 


     public function AdminPaymentShow(Transaction $transaction){

        $transaction->load(['user','plan','approvedBy']);
        return view('admin.backend.payment.payment_show',compact('transaction'));

     }
     // End Method 





} 
