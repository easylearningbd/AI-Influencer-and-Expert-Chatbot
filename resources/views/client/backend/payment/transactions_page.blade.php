@extends('client.client_dashboard')
@section('client') 
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Transaction History</h4>
        </div>
        <div>
            <span class="badge bg-info me-2">
                <i class="mdi mdi-wallet"></i> {{ auth()->user()->token_balance }} tokens
            </span>
            <a href="{{ route('user.plans') }}" class="btn btn-sm btn-primary">
                <i class="mdi mdi-plus"></i> Buy Tokens
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Transactions</h5>
                </div>
<div class="card-body">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Tokens</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    @forelse ($transactions as $transaction) 
        <tr>
            <td>
                <strong>{{ $transaction->transaction_id }}</strong>
            </td>
            <td>
                @if ($transaction->plan) 
                  {{ $transaction->plan->name }}
                  @else
                    <span class="text-muted">N/A</span>
                @endif
            </td>
            <td>
                <strong> ${{ number_format($transaction->amount, 2)  }}</strong>
            </td>
            <td>
                <span class="badge bg-info">{{ number_format($transaction->tokens)  }}</span>
            </td>
            <td>
                @if ($transaction->payment_method === 'bank_transfer') 
                    <i class="ri-bank-line"></i> Bank Transfer
                @elseif ($transaction->payment_method === 'card')
                    <i class="ri-id-card-line"></i> Card
                @elseif ($transaction->payment_method === 'stripe')
                    <i class="ri-paypal-fill"></i> PayPal
                @endif
                    
            </td>
            <td>
                
         @if ($transaction->status === 'pending')  
             <div class="badge bg-info">Pending</div>
            <br><small class="text-muted">Awaiting review</small>  
         @elseif ($transaction->status === 'completed')   
          <div class="badge bg-success">Completed</div>
            <br><small class="text-muted">Completed</small>
         @elseif ($transaction->status === 'rejected') 
          <div class="badge bg-danger">Rejected</div>
          <br><small class="text-muted">Rejected</small>
         @endif  
            </td>
            <td>
                {{ $transaction->created_at->format('M d, Y') }}<br>
                <small class="text-muted">{{ $transaction->created_at->format('g:i A') }}</small>
            </td>
            <td>
                <a href=" " class="btn btn-sm btn-primary">
                    <i class="mdi mdi-eye"></i> View
                </a>
            </td>
        </tr>
     @empty 
        <tr>
            <td colspan="8" class="text-center py-5">
                <i class="mdi mdi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
                <p class="text-muted mt-2">No transactions yet</p>
                <a href=" " class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Purchase Tokens
                </a>
            </td>
        </tr>
     @endforelse  
              
            </tbody>
        </table>
    </div>

    
    <div class="mt-3">
       {{ $transactions->links() }}
    </div>
    
</div>
            </div>
        </div>
    </div>

</div>
@endsection
