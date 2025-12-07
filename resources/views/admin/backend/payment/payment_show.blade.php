@extends('admin.admin_dashboard')

@section('admin')
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">
                <a href=" " class="text-muted"><i class="mdi mdi-arrow-left"></i></a>
                Transaction Details
            </h4>
        </div>
        <div>
           @if ($transaction->status === 'pending')     
          <div class="badge bg-info">Pending</div> 
           @elseif ($transaction->status === 'completed')
          <div class="badge bg-success">Completed</div>
           @elseif ($transaction->status === 'rejected') 
          <div class="badge bg-danger">Rejected</div> 
          @endif
        </div>
    </div>

    <div class="row">
        <!-- Transaction Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Transaction Information</h5>
                </div>
<div class="card-body">
    <table class="table table-borderless">
        <tr>
            <th width="30%">Transaction ID:</th>
            <td><strong>{{ $transaction->transaction_id }}</strong></td>
        </tr>
        <tr>
            <th>User:</th>
            <td>
                {{ $transaction->user->name }}<br>
                <small class="text-muted">{{ $transaction->user->email }}</small><br>
                <small class="text-muted">User ID: {{ $transaction->user->id }}</small>
            </td>
        </tr>
        <tr>
            <th>Plan:</th>
            <td>
                @if ($transaction->plan) 
                    <strong>{{ $transaction->plan->name }}</strong><br>
                    <small class="text-muted">{{ $transaction->plan->description }}</small>
                @else 
                    <span class="text-muted">N/A</span>
                 @endif
            </td>
        </tr>
        <tr>
            <th>Amount:</th>
            <td><h4 class="text-primary mb-0">${{ number_format($transaction->amount, 2) }}</h4></td>
        </tr>
        <tr>
            <th>Tokens:</th>
            <td><span class="badge bg-info" style="font-size: 1.1rem;">
                {{ number_format($transaction->tokens) }} tokens</span></td>
        </tr>
        <tr>
            <th>Payment Method:</th>
            <td>
                
               @if ($transaction->payment_method === 'bank_transfer') 
                    <i class="ri-bank-line"></i> Bank
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
           @elseif ($transaction->status === 'completed')
          <div class="badge bg-success">Completed</div>
           @elseif ($transaction->status === 'rejected') 
          <div class="badge bg-danger">Rejected</div> 
          @endif
        </td>
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
        
         @if ($transaction->approved_by)
        <tr>
            <th>Processed By:</th>
            <td>{{ $transaction->approvedBy->name }}</td>
        </tr>
         @endif
    </table>

     @if ($transaction->bank_details)
    <hr>
    <h6>Bank Transfer Details</h6>
    <div class="alert alert-light">
        {{ $transaction->bank_details }}
    </div>
    @endif

    @if ($transaction->admin_notes)
    <hr>
    <h6>Admin Notes</h6>
    <div class="alert alert-warning">
        {{ $transaction->admin_notes }}
    </div>
     @endif
</div>
</div>

            <!-- Payment Proof -->
            
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Payment Proof</h5>
        <a href=" " class="btn btn-sm btn-primary">
            <i class="mdi mdi-download"></i> Download
        </a>
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
            <p class="text-muted">Unable to preview this file type. Please download to view.</p>
          @endif
    </div>
</div>
           
        </div>

        <!-- Actions -->
        <div class="col-md-4">
   @if ($transaction->isPending()) 
            <!-- Approve Form -->
<div class="card mb-3 border-success">
    <div class="card-header bg-success text-white">
        <h6 class="mb-0"><i class="mdi mdi-check-circle"></i> Approve Payment</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.payment.approve',$transaction->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Admin Notes (Optional)</label>
                <textarea name="admin_notes" class="form-control" rows="3" placeholder="Add any notes about this approval..."></textarea>
            </div>
            <div class="alert alert-info">
                <small>
                    <i class="mdi mdi-information"></i>
                    This will add <strong>{{ number_format($transaction->tokens) }} tokens</strong> to <strong>{{ $transaction->user->name }}</strong>  account.
                </small>
            </div>
            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Are you sure you want to approve this payment?')">
                <i class="mdi mdi-check-circle"></i> Approve Payment
            </button>
        </form>
    </div>
</div>

            <!-- Reject Form -->
<div class="card border-danger">
    <div class="card-header bg-danger text-white">
        <h6 class="mb-0"><i class="mdi mdi-close-circle"></i> Reject Payment</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.payment.reject',$transaction->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                <textarea name="admin_notes" class="form-control" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
            </div>
            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to reject this payment?')">
                <i class="mdi mdi-close-circle"></i> Reject Payment
            </button>
        </form>
    </div>
</div>

@else
<div class="card">
    <div class="card-body text-center">
        <p>This transaction has been {{ $transaction->status }}</p>
    </div> 
</div>       
@endif 
 

            <!-- User Information -->
<div class="card mt-3">
    <div class="card-header">
        <h6 class="mb-0">User Account Info</h6>
    </div>
    <div class="card-body">
        <table class="table table-sm table-borderless mb-0">
            <tr>
                <td><strong>Current Balance:</strong></td>
                <td class="text-end">
                    <span class="badge bg-info">{{ number_format($transaction->user->token_balance) }}</span>
                </td>
            </tr>
            <tr>
                <td><strong>Plan Type:</strong></td>
                <td class="text-end">{{ ucfirst($transaction->user->plan_type) }}</td>
            </tr>
            <tr>
                <td><strong>Total Transactions:</strong></td>
                <td class="text-end">{{ $transaction->user->transactions()->count() }}</td>
            </tr>
            <tr>
                <td><strong>Member Since:</strong></td>
                <td class="text-end">{{ $transaction->user->created_at->format('M Y') }}</td>
            </tr>
        </table>
    </div>
</div>
        </div>
    </div>

</div>
@endsection
