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
          status_badge
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
            <td><strong>transaction_id</strong></td>
        </tr>
        <tr>
            <th>User:</th>
            <td>
                name<br>
                <small class="text-muted">email</small><br>
                <small class="text-muted">User ID: user</small>
            </td>
        </tr>
        <tr>
            <th>Plan:</th>
            <td>
                
                    <strong>name</strong><br>
                    <small class="text-muted">description</small>
                
                    <span class="text-muted">N/A</span>
                
            </td>
        </tr>
        <tr>
            <th>Amount:</th>
            <td><h4 class="text-primary mb-0">$amount</h4></td>
        </tr>
        <tr>
            <th>Tokens:</th>
            <td><span class="badge bg-info" style="font-size: 1.1rem;">tokens tokens</span></td>
        </tr>
        <tr>
            <th>Payment Method:</th>
            <td>
                
                    <i class="ri-bank-line"></i> Bank Transfer
                    
                    <i class="ri-id-card-line"></i> Credit/Debit Card
                    
                    <i class="ri-paypal-fill"></i> PayPal
                
            </td>
        </tr>
        <tr>
            <th>Status:</th>
            <td>status_badge</td>
        </tr>
        <tr>
            <th>Submitted Date:</th>
            <td>created_at</td>
        </tr>
        
        <tr>
            <th>status Date:</th>
            <td>approved_at</td>
        </tr>
        
        
        <tr>
            <th>Processed By:</th>
            <td>approvedBy</td>
        </tr>
        
    </table>

    
    <hr>
    <h6>Bank Transfer Details</h6>
    <div class="alert alert-light">
        bank_details
    </div>
    

    
    <hr>
    <h6>Admin Notes</h6>
    <div class="alert alert-warning">
        admin_notes
    </div>
    
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

        
            <img src=" " alt="Payment Proof" class="img-fluid" style="max-height: 600px;">
        
            <embed src=" " type="application/pdf" width="100%" height="600px">
        
            <p class="text-muted">Unable to preview this file type. Please download to view.</p>
        
    </div>
</div>
           
        </div>

        <!-- Actions -->
        <div class="col-md-4">
           
            <!-- Approve Form -->
<div class="card mb-3 border-success">
    <div class="card-header bg-success text-white">
        <h6 class="mb-0"><i class="mdi mdi-check-circle"></i> Approve Payment</h6>
    </div>
    <div class="card-body">
        <form action=" " method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Admin Notes (Optional)</label>
                <textarea name="admin_notes" class="form-control" rows="3" placeholder="Add any notes about this approval..."></textarea>
            </div>
            <div class="alert alert-info">
                <small>
                    <i class="mdi mdi-information"></i>
                    This will add <strong>tokens tokens</strong> to name's account.
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
        <form action=" " method="POST">
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
                    <span class="badge bg-info">token_balancetokens</span>
                </td>
            </tr>
            <tr>
                <td><strong>Plan Type:</strong></td>
                <td class="text-end">plan_type</td>
            </tr>
            <tr>
                <td><strong>Total Transactions:</strong></td>
                <td class="text-end">transactions</td>
            </tr>
            <tr>
                <td><strong>Member Since:</strong></td>
                <td class="text-end">created_at</td>
            </tr>
        </table>
    </div>
</div>
        </div>
    </div>

</div>
@endsection
