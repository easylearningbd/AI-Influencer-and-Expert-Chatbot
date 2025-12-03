@extends('client.client_dashboard')
@section('client')
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">
                <a href=" " class="text-muted"><i class="mdi mdi-arrow-left"></i></a>
                Subscribe to {{ $plan->name }}
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Subscription Payment</h5>
                </div>
                <div class="card-body">
<form action="{{ route('user.subscription.payment',$plan->slug) }}" method="POST" enctype="multipart/form-data" id="payment-form">
    @csrf

    <!-- Payment Method Selection -->
    <div class="mb-4">
        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
        <div class="row">
            <div class="col-md-4">
                <div class="form-check form-check-card">
                    <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer" checked>
                    <label class="form-check-label w-100" for="bank_transfer">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="mdi mdi-bank" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2">Bank Transfer</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check form-check-card">
                    <input class="form-check-input" type="radio" name="payment_method" id="card" value="card" disabled>
                    <label class="form-check-label w-100" for="card">
                        <div class="card">
                            <div class="card-body text-center opacity-50">
                                <i class="mdi mdi-credit-card" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2">Card</p>
                                <small class="badge bg-secondary">Coming Soon</small>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check form-check-card">
                    <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal" disabled>
                    <label class="form-check-label w-100" for="paypal">
                        <div class="card">
                            <div class="card-body text-center opacity-50">
                                <i class="mdi mdi-paypal" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2">PayPal</p>
                                <small class="badge bg-secondary">Coming Soon</small>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
        @error('payment_method')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <!-- Bank Transfer Details (shown by default) -->
    <div id="bank-transfer-section">
        <div class="alert alert-info">
            <h6><i class="mdi mdi-information"></i> Bank Transfer Instructions</h6>
            <p class="mb-2">Please transfer the subscription amount to the following bank account:</p>
            <hr>
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td width="40%"><strong>Bank Name:</strong></td>
                    <td>AI Influencer Chat Bank</td>
                </tr>
                <tr>
                    <td><strong>Account Name:</strong></td>
                    <td>AI Influencer Chat LLC</td>
                </tr>
                <tr>
                    <td><strong>Account Number:</strong></td>
                    <td class="fw-bold text-dark">1234567890123</td>
                </tr>
                <tr>
                    <td><strong>Routing Number / SWIFT:</strong></td>
                    <td class="fw-bold text-dark">AICHAT123XXX</td>
                </tr>
                <tr>
                    <td><strong>Account Type:</strong></td>
                    <td>Business Checking</td>
                </tr>
                <tr class="border-top">
                    <td><strong>Amount to Transfer:</strong></td>
                    <td class="text-primary"><h5 class="mb-0">${{ $plan->price }}</h5></td>
                </tr>
                <tr>
                    <td><strong>Reference/Note:</strong></td>
                    <td>Your Email: {{ auth()->user()->email }}</td>
                </tr>
            </table>
            <hr>
            <div class="alert alert-warning mb-0">
                <small>
                    <i class="mdi mdi-alert"></i> <strong>Important:</strong> Please include your email address ({{ auth()->user()->email }}) in the transfer note/reference so we can identify your payment.
                </small>
            </div>
        </div>

        <!-- Bank Transfer Details -->
        <div class="mb-3">
            <label for="bank_details" class="form-label">Transfer Details / Transaction Reference</label>
            <textarea class="form-control" id="bank_details" name="bank_details" rows="3" placeholder="Enter your transaction reference number, bank name, or any transfer details..."></textarea>
            <small class="text-muted">Please provide the transaction reference or any details about your bank transfer.</small>
            @error('bank_details')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Payment Proof Upload -->
        <div class="mb-3">
            <label for="payment_proof" class="form-label">Payment Proof (Screenshot/Receipt) <span class="text-danger">*</span></label>
            <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*,.pdf" required>
            <small class="text-muted">Upload a screenshot or receipt of your bank transfer (JPG, PNG, or PDF - Max 5MB)</small>
            @error('payment_proof')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Submit Button -->
    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="mdi mdi-check-circle"></i> Complete Subscription
        </button>
        
    </div>
</form>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Subscription Summary</h5>
                </div>
                <div class="card-body">
                    <h4 class="mb-3">{{ $plan->name }}</h4>

                    <table class="table table-borderless">
                        <tr>
                            <td>Monthly Tokens:</td>
                            <td class="text-end"><strong>{{ number_format(  $plan->tokens ) }}</strong></td>
                        </tr>
                         
                        <tr>
                            <td>Billing:</td>
                            <td class="text-end">{{ ucfirst($plan->type) }} Recurring</td>
                        </tr>
                        <tr class="border-top">
                            <td><strong>Monthly Price:</strong></td>
                            <td class="text-end"><h4 class="text-primary mb-0">$ {{ $plan->price }}/mo</h4></td>
                        </tr>
                    </table>

                   @if ($plan->features && count($plan->features) > 0) 
                    <hr>
                    <h6>Included Features:</h6>
                    <ul class="list-unstyled">
                    @foreach ($plan->features as $feature) 
                        <li class="mb-2">
                            <i class="mdi mdi-check-circle text-success"></i> 
                            {{ $feature }}
                        </li>
                    @endforeach 
                    </ul>
                    @endif

                    <hr>
                    <div class="alert alert-success mb-0">
                        <small>
                            <i class="mdi mdi-autorenew"></i> <strong>Auto-Renewal:</strong> Your subscription will automatically renew each month. You can cancel anytime.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6><i class="mdi mdi-shield-check text-success"></i> Secure Payment</h6>
                    <p class="text-muted small mb-0">Your payment information is processed securely. We do not store credit card details.</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6><i class="mdi mdi-clock-outline text-info"></i> Processing Time</h6>
                    <p class="text-muted small mb-0">Bank transfer payments are usually approved within 24 hours during business days.</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6><i class="mdi mdi-calendar-check text-warning"></i> Tokens Reset Monthly</h6>
                    <p class="text-muted small mb-0">Your tokens will reset on the same day each month. Unused tokens do not roll over.</p>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.form-check-card .form-check-input {
    display: none;
}

.form-check-card .card {
    cursor: pointer;
    transition: all 0.3s;
}

.form-check-card input:checked + label .card {
    border-color: #0d6efd;
    background-color: #e7f1ff;
}

.form-check-card input:disabled + label .card {
    cursor: not-allowed;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // Form validation 
    document.getElementById('payment-form').addEventListener('submit', function(e){
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');

        if (!paymentMethod) {
            e.preventDefault();
            alert('Please select a payment method');
            return false;
        }
    });
});

</script>

@endsection
