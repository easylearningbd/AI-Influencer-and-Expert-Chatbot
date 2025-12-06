@extends('client.client_dashboard')
@section('client') 
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Token Balance & Usage</h4>
        </div>
        <div>
            <a href="{{ route('user.plans') }}" class="btn btn-sm btn-primary">
                <i class="mdi mdi-plus"></i> Buy More Tokens
            </a>
        </div>
    </div>

    <!-- Balance Overview -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-0">Current Balance</h6>
                            <h2 class="mb-0">{{ number_format($user->token_balance) }}</h2>
                            <small>Tokens Available</small>
                        </div>
                        <i class="mdi mdi-wallet" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-0">Total Messages</h6>
                            <h2 class="mb-0">{{ number_format($totalMessages) }}</h2>
                            <small>Sent to Influencers</small>
                        </div>
                        <i class="mdi mdi-message-text" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-0">Tokens Used</h6>
                            <h2 class="mb-0">{{ number_format($tokensUsed) }}</h2>
                            <small>5 per message</small>
                        </div>
                        <i class="mdi mdi-chart-line" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Token Purchase History -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Token Purchases</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
<table class="table table-hover">
    <thead>
        <tr>
            <th>Date</th>
            <th>Plan</th>
            <th>Tokens</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($recentTransactions as $transaction) 
        <tr>
            <td>{{ $transaction->approved_at->format('M d, Y') }}</td>
            <td>
                @if ($transaction->plan)
                    {{ $transaction->plan->name }}
                @else
                    N/A
                @endif
                 
            </td>
            <td>
                <span class="badge bg-success">+{{ number_format($transaction->tokens) }}</span>
            </td>
            <td>${{ number_format($transaction->amount, 2) }}</td>
        </tr>
         @empty
        <tr>
            <td colspan="4" class="text-center text-muted py-3">
                No token purchases yet
            </td>
        </tr>
       @endforelse   
    </tbody>
</table>
                    </div>

            @if (count($recentTransactions) > 0) 
            <div class="text-center mt-3">
                <a href="{{ route('user.transactions') }}" class="btn btn-outline-primary btn-sm">
                    View All Transactions
                </a>
            </div>
            @endif     
        </div>
    </div>
</div>

        <!-- Usage Information -->
<div class="col-md-6">
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Token Usage Info</h5>
    </div>
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <td><i class="mdi mdi-message text-primary"></i> Cost per message:</td>
                <td class="text-end"><strong>5 tokens</strong></td>
            </tr>
            <tr>
                <td><i class="mdi mdi-wallet text-success"></i> Current balance:</td>
                <td class="text-end"><strong>{{ number_format($user->token_balance) }} tokens</strong></td>
            </tr>
            <tr>
                <td><i class="mdi mdi-calculator text-info"></i> Messages you can send:</td>
                <td class="text-end"><strong>~{{ floor($user->token_balance / 5) }} messages</strong></td>
            </tr>
            <tr class="border-top">
                <td><i class="mdi mdi-chart-line text-warning"></i> Total tokens used:</td>
                <td class="text-end"><strong>{{ number_format($tokensUsed) }} tokens</strong></td>
            </tr>
            <tr>
                <td><i class="mdi mdi-message-text text-danger"></i> Total messages sent:</td>
                <td class="text-end"><strong>{{ number_format($totalMessages) }} messages</strong></td>
            </tr>
        </table>

        <hr>

        <div class="alert alert-info mb-0">
            <h6><i class="mdi mdi-information"></i> How Tokens Work</h6>
            <ul class="mb-0 ps-3">
                <li>Each message to an AI influencer costs 5 tokens</li>
                <li>Tokens are deducted when you send a message</li>
                <li>Purchase more tokens anytime from the plans page</li>
                <li>Your token balance never expires</li>
            </ul>
        </div>
    </div>
</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-4">
                    <h5>Need More Tokens?</h5>
                    <p class="text-muted">Purchase tokens to continue chatting with your favorite AI influencers</p>
                    <a href="{{ route('user.plans') }}" class="btn btn-primary me-2">
                        <i class="mdi mdi-wallet"></i> View Plans
                    </a>
                    <a href=" " class="btn btn-outline-primary">
                        <i class="mdi mdi-message"></i> Start Chatting
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
