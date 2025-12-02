@extends('client.client_dashboard')
@section('client')
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Manage Subscription</h4>
        </div>
        <div class="text-sm-end">
            <a href="{{ route('user.plans') }}" class="btn btn-outline-primary">
                <i class="mdi mdi-arrow-left me-1"></i> Back to Plans
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="mdi mdi-alert-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Active Subscription -->
 @if ($activeSubscription) 
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-3">
            <i class="mdi mdi-crown text-warning me-2"></i>
            Current Subscription
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <h6 class="text-muted mb-1">Plan</h6>
                <h4 class="mb-0">{{ $activeSubscription->plan->name }}</h4>
                <p class="text-muted mb-0">${{ $activeSubscription->plan->price }} / {{ $activeSubscription->plan->type }}</p>
            </div>

            <div class="col-md-6 mb-3">
                <h6 class="text-muted mb-1">Status</h6>
@if ($activeSubscription->status == 'active') 
    <span class="badge text-bg-success">Active</span>
@elseif ($activeSubscription->status == 'cancelled')
    <span class="badge text-bg-danger">Cancelled</span>
@elseif ($activeSubscription->status == 'pending')
    <span class="badge text-bg-warning">Pending</span>
@else
    <span class="badge text-bg-secondary">{{ ucfirst($activeSubscription->status)  }}</span>
@endif 
            </div>

            <div class="col-md-6 mb-3">
                <h6 class="text-muted mb-1">Monthly Tokens</h6>
                <h5 class="mb-0">{{ number_format($activeSubscription->monthly_tokens) }} tokens</h5>
            </div>

            <div class="col-md-6 mb-3">
                <h6 class="text-muted mb-1">Tokens Remaining</h6>
                <h5 class="mb-0 text-danger">
                    {{ number_format($activeSubscription->tokens_remaining) }} tokens
                </h5>
                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar bg-danger"
                            style="width: {{ ($activeSubscription->tokens_remaining / $activeSubscription->monthly_tokens) * 100}}  %"></div>
                </div>
            </div>

            @if ($activeSubscription->current_period_start) 
            <div class="col-md-6 mb-3">
                <h6 class="text-muted mb-1">Current Period</h6>
                <p class="mb-0">{{ $activeSubscription->current_period_start->format('M d, Y') }} - {{ $activeSubscription->current_period_end->format('M d, Y') }}</p>
            </div>
            @endif

             @if ($activeSubscription->next_billing_date) 
            <div class="col-md-6 mb-3">
                <h6 class="text-muted mb-1">Next Billing Date</h6>
                <p class="mb-0">{{ $activeSubscription->next_billing_date->format('M d, Y') }}</p>
            </div>
             @endif
        </div>

        <hr>

        <div class="d-flex gap-2">
            
                <form action=" " method="POST"
                        onsubmit="return confirm('Are you sure you want to cancel this subscription?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="mdi mdi-cancel me-1"></i> Cancel Subscription
                    </button>
                </form>
            

            <a href=" " class="btn btn-primary">
                <i class="mdi mdi-swap-horizontal me-1"></i> Change Plan
            </a>
        </div>
    </div>
</div>
@else 
            
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="mdi mdi-alert-circle-outline text-muted" style="font-size: 48px;"></i>
            <h4 class="mt-3">No Active Subscription</h4>
            <p class="text-muted">You don't have any active subscription. Choose a plan to get started!</p>
            <a href=" " class="btn btn-primary">
                <i class="mdi mdi-crown me-1"></i> View Plans
            </a>
        </div>
    </div>
 @endif         

            <!-- Subscription History -->
<div class="card mt-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Subscription History</h5>
    </div>
    <div class="card-body">
@if ($allSubscritions->count() > 0)          
<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th>Plan</th>
                <th>Status</th>
                <th>Period</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
    @foreach ($allSubscritions as $sub)      
    <tr>
        <td>
            <strong>{{ $sub->plan->name }}</strong><br>
            <small class="text-muted">${{ $sub->plan->price }} /{{ $sub->plan->type }}</small>
        </td>
        <td>
           @if ($sub->status == 'active') 
            <span class="badge text-bg-success">Active</span>
            @elseif ($sub->status == 'cancelled')
                <span class="badge text-bg-danger">Cancelled</span>
            @elseif ($sub->status == 'pending')
                <span class="badge text-bg-warning">Pending</span>
            @else
                <span class="badge text-bg-secondary">{{ ucfirst($sub->status)  }}</span>
            @endif 
        </td>
        <td> 
            @if ($sub->current_period_start && $sub->current_period_end)
                {{ $sub->current_period_start->format('M d') }} - 
                {{ $sub->current_period_end->format('M d, Y') }}
            @else
                <span class="text-muted">-</span>
            @endif
        </td>
        <td>{{ $sub->created_at->format('M d, Y') }}</td>
    </tr> 
    @endforeach      
            
        </tbody>
    </table>
</div>
@else 
<div class="text-center py-3">
    <p class="text-muted mb-0">No subscription history found.</p>
</div>
@endif  
                  
                </div>
            </div>
        </div>

<div class="col-lg-4">
    <!-- Usage Summary -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Usage Summary</h5>
        </div>
        <div class="card-body">
           @if ($activeSubscription && $activeSubscription->isActive()) 
            
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Tokens Used This Month</span>
                    <span class="fw-semibold">{{ number_format($activeSubscription->tokens_used_this_month)  }}</span>
                </div>
                <div class="progress" style="height: 8px;">
    <div class="progress-bar bg-primary"
            style="width: {{ ($activeSubscription->tokens_used_this_month / $activeSubscription->monthly_tokens ) * 100}}  %"></div>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Tokens Remaining</span>
                    <span class="fw-semibold text-success">{{ number_format($activeSubscription->tokens_remaining) }}</span>
                </div>
            </div>

            <div class="alert alert-info mb-0">
                <i class="mdi mdi-information me-2"></i>
                <small>Your tokens reset on {{ $activeSubscription->next_billing_date ? $activeSubscription->next_billing_date->format('M d, Y') :  'next billing date'}}  </small>
            </div>
        @else 
            <div class="text-center py-3">
                <p class="text-muted mb-0">No active subscription to show usage.</p>
            </div>
        @endif   
        </div>
    </div>

    <!-- Help -->
    <div class="card mt-3">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Need Help?</h6>
            <ul class="list-unstyled mb-0">
                <li class="mb-2">
                    <i class="mdi mdi-help-circle text-primary me-1"></i>
                    <a href="#" class="text-muted">How subscriptions work</a>
                </li>
                <li class="mb-2">
                    <i class="mdi mdi-help-circle text-primary me-1"></i>
                    <a href="#" class="text-muted">Billing & Payments</a>
                </li>
                <li class="mb-2">
                    <i class="mdi mdi-help-circle text-primary me-1"></i>
                    <a href="#" class="text-muted">Contact Support</a>
                </li>
            </ul>
        </div>
    </div>
</div>
    </div>
</div>
@endsection
