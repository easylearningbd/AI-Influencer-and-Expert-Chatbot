@extends('admin.admin_dashboard')

@section('admin')
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Payment Management</h4>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-0">Pending</h6>
                            <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                        </div>
                        <i class="mdi mdi-clock-outline" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-0">Completed</h6>
                            <h3 class="mb-0">{{ $stats['completed'] }}</h3>
                        </div>
                        <i class="mdi mdi-check-circle-outline" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-0">Rejected</h6>
                            <h3 class="mb-0">{{ $stats['rejected'] }}</h3>
                        </div>
                        <i class="mdi mdi-close-circle-outline" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-0">Total Revenue</h6>
                            <h3 class="mb-0">${{ number_format($stats['total_revenue'],2) }}</h3>
                        </div>
                        <i class="mdi mdi-cash-multiple" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
    <form method="GET" action="{{ route('admin.payment.index') }}" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }} >Pending</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }} >Completed</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }} >Rejected</option>
                <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }} >Refunded</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Payment Method</label>
            <select name="payment_method" class="form-select">
                <option value="">All Methods</option>
                <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }} >Bank Transfer</option>
                <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }} >Card</option>
                <option value="paypal" {{ request('payment_method') === 'paypal' ? 'selected' : '' }} >PayPal</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Date From</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">Date To</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="mdi mdi-filter"></i> Filter
            </button>
        </div>
    </form>
        </div>
    </div>

    <!-- Search -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.payment.index') }}" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Search by transaction ID, user name, or email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="mdi mdi-magnify"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
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
            <th>User</th>
            <th>Plan</th>
            <th>Amount</th>
            <th>Tokens</th>
            <th>Method</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        
        <tr>
            <td>
                <strong> transaction_id</strong>
            </td>
            <td>
                name<br>
                <small class="text-muted">email</small>
            </td>
            <td>
                name
                    <span class="text-muted">N/A</span>
                
            </td>
            <td>
                <strong>$amount</strong>
            </td>
            <td>
                <span class="badge bg-info">tokens</span>
            </td>
            <td>
                
                    <i class="ri-bank-line"></i> Bank
                
                    <i class="ri-id-card-line"></i> Card
                
                        <i class="ri-paypal-fill"></i> PayPal
                
                
                    <br><small class="badge bg-success"><i class="mdi mdi-file-check"></i> Proof</small>
                
            </td>
            <td>
               
          <div class="badge bg-info">Pending</div> 
          <div class="badge bg-success">Completed</div> 
          <div class="badge bg-danger">Rejected</div> 
        
            </td>
            <td>
                created_at<br>
                <small class="text-muted">created_at</small>
            </td>
            <td>
                <a href=" " class="btn btn-sm btn-primary">
                    <i class="mdi mdi-eye"></i> View
                </a>
            </td>
        </tr>
      
        <tr>
            <td colspan="9" class="text-center text-muted py-4">
                <i class="mdi mdi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                <p>No transactions found</p>
            </td>
        </tr>
        
    </tbody>
</table>
</div>

             
            <div class="mt-3">
             links
            </div>
             
        </div>
    </div>

</div>
@endsection
