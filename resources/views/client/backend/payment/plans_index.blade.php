@extends('client.client_dashboard')
@section('client')
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Subscription Plans</h4>
        </div>
        <div>
    @if ($activeSubscription) 
    <span class="badge bg-success me-2">
        <i class="mdi mdi-check-circle"></i> Active: {{ $activeSubscription->plan->name  }}
    </span>
    <span class="badge bg-info me-2">
        <i class="mdi mdi-wallet"></i> {{ $activeSubscription->tokens_remaining }} / {{ $activeSubscription->monthly_tokens }} tokens
    </span>
    @else
    <span class="badge bg-warning me-2">
        <i class="mdi mdi-alert"></i> No Active Subscription
    </span>
     @endif    

<a href=" " class="btn btn-sm btn-primary">
    <i class="mdi mdi-cog"></i> Manage Subscription
</a>
        </div>
    </div>

    @if ($activeSubscription) 
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success">
                <i class="mdi mdi-check-circle"></i>
                <strong>Your Current Plan:</strong> You are subscribed to <strong>{{ $activeSubscription->plan->name  }}</strong>.
                You have <strong>tokens_remaining tokens remaining</strong> this month.
                Resets on {{ $activeSubscription->current_period_end->format('M d, Y')  }}.
            </div>
        </div>
    </div>
     @endif
 

    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="mdi mdi-information"></i>
                <strong>How it works:</strong> Subscribe to a monthly plan and get tokens every month. Each message costs <strong>5</strong> tokens. Unused tokens reset at the start of each billing cycle.
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card h-100 border-success">
               
    <div class="card-header bg-success text-white text-center">
        <i class="mdi mdi-check-circle"></i> Your Current Plan
    </div>
    
    <div class="card-header bg-primary text-white text-center">
        <i class="mdi mdi-star"></i> Most Popular
    </div>
    

    <div class="card-body text-center">
        <h5 class="card-title">name</h5>

        <div class="my-4">
            <h2 class="text-primary mb-0">$ price</h2>
            
                <small class="text-muted">/ type</small>
            
        </div>

        <div class="mb-3">
            <span class="badge bg-success" style="font-size: 1.1rem; padding: 8px 16px;">
                3 Tokens
            </span>
        </div>

        <div class="text-muted small mb-3">
            price_per_token per token
        </div>

        
        <hr>
        <ul class="list-unstyled text-start">
            
            <li class="mb-2">
                <i class="mdi mdi-check text-success"></i> feature
            </li>
            
        </ul>
                    

                   
     <p class="text-muted small mt-3">description</p>
                    
                </div>

<div class="card-footer bg-transparent">
    
        <button class="btn btn-success w-100" disabled>
            <i class="mdi mdi-check-circle"></i> Current Plan
        </button>
        
        <form action=" " method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-warning w-100">
                <i class="mdi mdi-swap-horizontal"></i> Switch Plan
            </button>
        </form>
    
        <form action=" " method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary w-100">
                <i class="mdi mdi-cart"></i> Subscribe Now
            </button>
        </form>
                   
                </div>
            </div>
        </div>
        
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-package-variant" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="text-muted mt-3">No plans available at the moment</h5>
                    <p class="text-muted">Please check back later for token packages.</p>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Token Usage Info -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Token Usage</h6>
                </div>
                <div class="card-body">
    <table class="table table-sm">
        <tbody>
            <tr>
                <td><i class="mdi mdi-message"></i> Per message</td>
                <td class="text-end"><strong> 5 tokens</strong></td>
            </tr>
            
            <tr class="table-success">
                <td><i class="mdi mdi-autorenew"></i> Subscription tokens</td>
                <td class="text-end"><strong>tokens_remaining /monthly_tokens tokens</strong></td>
            </tr>
            <tr>
                <td><i class="mdi mdi-calendar"></i> Next reset</td>
                <td class="text-end"><strong>current_period_end</strong></td>
            </tr>
            <tr>
                <td><i class="mdi mdi-calculator"></i> Available messages</td>
                <td class="text-end"><strong>~tokens_remaining messages</strong></td>
            </tr>
            
            <tr class="table-warning">
                <td colspan="2" class="text-center">
                    <i class="mdi mdi-alert"></i> No active subscription
                </td>
            </tr>
            
        </tbody>
    </table>
                </div>
            </div>
        </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Payment Methods</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="mdi mdi-bank text-primary"></i> Bank Transfer (Manual Approval)
                    </li>
                    <li class="mb-2">
                        <i class="mdi mdi-credit-card text-success"></i> Credit/Debit Card (Coming Soon)
                    </li>
                    <li class="mb-2">
                        <i class="mdi mdi-paypal text-info"></i> PayPal (Coming Soon)
                    </li>
                </ul>
                <div class="alert alert-warning mt-3 mb-0">
                    <small>
                        <i class="mdi mdi-information"></i>
                        Bank transfer payments require admin approval (usually within 24 hours)
                    </small>
                </div>
            </div>
        </div>
    </div>
    </div>

</div>
@endsection
