@extends('client.client_dashboard')
@section('client') 
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">
                <a href="#" class="text-muted"><i class="mdi mdi-arrow-left"></i></a>
                Transaction Details
            </h4>
        </div>
        <div>
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
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Transaction Information</h5>
                </div>
                <div class="card-body">
    <table class="table table-borderless">
        <tr>
            <th width="35%">Transaction ID:</th>
            <td><strong>{{ $transaction->transaction_id }}</strong></td>
        </tr>
        <tr>
            <th>Plan:</th>
            <td>
                @if ($transaction->plan) 
                  <strong>{{ $transaction->plan->name }}</strong>
                  @else
                    <span class="text-muted">N/A</span>
                @endif 
                
            </td>
        </tr>
        <tr>
            <th>Amount Paid:</th>
            <td><h4 class="text-primary mb-0">$ {{ number_format($transaction->amount,2) }}</h4></td>
        </tr>
        <tr>
            <th>Tokens Purchased:</th>
            <td><span class="badge bg-info" style="font-size: 1.1rem;">{{ number_format($transaction->tokens) }} tokens</span></td>
        </tr>
        <tr>
            <th>Payment Method:</th>
            <td>
                
            @if ($transaction->payment_method === 'bank_transfer') 
                <i class="ri-bank-line"></i> Bank Transfer
            @elseif ($transaction->payment_method === 'card')
                <i class="ri-id-card-line"></i> Card
            @elseif ($transaction->payment_method === 'stripe')
                <i class="ri-paypal-fill"></i> PayPal
            @endif
                
            </td>
        </tr>
        <tr>
            <th>Status:</th>
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
         @endif </td>
        </tr>
        <tr>
            <th>Submitted Date:</th>
            <td>{{ $transaction->created_at->format('F d, Y g:i A') }}</td>
        </tr>
       @if ($transaction->approved_at) 
        <tr>
            <th>{{ ucfirst($transaction->status) }} Date:</th>
            <td>{{ $transaction->approved_at->format('F d, Y g:i A') }}</td>
        </tr>
       @endif 
    </table>

    @if ($transaction->bank_details) 
    <hr>
    <h6>Your Bank Transfer Details</h6>
    <div class="alert alert-light">
        {{ $transaction->bank_details }}
    </div>
     @endif

    @if ($transaction->admin_notes)
    <hr>
    <h6>Admin Notes</h6>
    <div class="alert alert-{{ $transaction->status === 'rejected' ? 'danger' : 'info' }} ">
       {{ $transaction->admin_notes }}
    </div>
     @endif
    

    @if ($transaction->status === 'pending') 
    <hr>
    <div class="alert alert-warning">
        <i class="mdi mdi-clock-outline"></i>
        <strong>Payment Under Review</strong>
        <p class="mb-0">Your payment is currently being reviewed by our team. This usually takes 24-48 hours during business days. You will receive a notification once it's approved.</p>
    </div>
    @elseif ($transaction->status === 'completed') 
    <hr>
    <div class="alert alert-success">
        <i class="mdi mdi-check-circle"></i>
        <strong>Payment Approved!</strong>
        <p class="mb-0">tokens tokens have been added to your account.</p>
    </div>
     @elseif ($transaction->status === 'rejected') 
    <hr>
    <div class="alert alert-danger">
        <i class="mdi mdi-close-circle"></i>
        <strong>Payment Rejected</strong>
        <p class="mb-0">Your payment was not approved. Please check the admin notes above for the reason. If you believe this is an error, please contact support.</p>
    </div>
    @elseif ($transaction->status === 'refunded') 
    <hr>
    <div class="alert alert-info">
        <i class="mdi mdi-undo"></i>
        <strong>Payment Refunded</strong>
        <p class="mb-0">This payment has been refunded and the tokens have been deducted from your account.</p>
    </div>
   @endif   
</div>
</div>

 @if ($transaction->payment_proof) 
<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0">Payment Proof</h5>
    </div>
    <div class="card-body text-center">
        @php
            $extension = pathinfo($transaction->payment_proof, PATHINFO_EXTENSION);
        @endphp 
        @if (in_array($extension, ['jpg','jpeg','png'])) 
            <img src="{{ asset($transaction->payment_proof) }}" alt="Payment Proof" class="img-fluid" style="max-height: 600px;">
        @elseif ($extension === 'pdf')
            <embed src="{{ asset($transaction->payment_proof) }}" type="application/pdf" width="100%" height="600px">
        @else 
            <p class="text-muted">Payment proof file uploaded</p>
         @endif
    </div>
</div>
 @endif   
            
        </div>

<div class="col-md-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">Order Summary</h6>
        </div>
        <div class="card-body">
           @if ($transaction->plan) 
            <h5>{{ $transaction->plan->name }}</h5>
            <table class="table table-borderless table-sm">
                <tr>
                    <td>Tokens:</td>
                    <td class="text-end"><strong>{{ number_format($transaction->tokens) }}</strong></td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td class="text-end"><strong>${{ number_format($transaction->amount, 2) }}</strong></td>
                </tr> 
            </table>
          @endif 
        </div>
    </div>

<div class="card mt-3">
    <div class="card-header">
        <h6 class="mb-0">Current Account Balance</h6>
    </div>
    <div class="card-body text-center">
        <h2 class="text-primary">{{ number_format(auth()->user()->token_balance) }}</h2>
        <p class="text-muted mb-0">Available Tokens</p>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <a href="{{ route('user.transactions') }}" class="btn btn-outline-primary w-100 mb-2">
            <i class="mdi mdi-arrow-left"></i> Back to Transactions
        </a>
        <a href="{{ route('user.plans') }}" class="btn btn-primary w-100">
            <i class="mdi mdi-plus"></i> Buy More Tokens
        </a>
    </div>
</div>
        </div>
    </div>

</div>
@endsection
