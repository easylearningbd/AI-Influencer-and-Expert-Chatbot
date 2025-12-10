@extends('client.client_dashboard')
@section('client') 
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Build Your Expert Team</h4>
            <p class="text-muted">Your personal team ready to help anytime you need them</p>
        </div>
    </div>
 
    <!-- Header Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="text-white mb-2">Professional AI Coaching</h5>
                    <p class="text-white-50 mb-0">Get expert guidance from AI coaches in career, fitness, finance, and nutrition. First session only 50 tokens, then unlimited free coaching!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Coaches Cards -->
    <div class="row">
    @foreach ($coaches as $coach) 
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
<div class="row align-items-center mb-3">
    <div class="col-auto">
        <div class="avatar-lg">
          @if ($coach->avater) 
            <img src="{{ asset($coach->avater) }}" alt=" " class="img-fluid rounded-circle">
         @else
            <span class="avatar-title rounded-circle bg-soft-{{ $coach->speciality == 'career' ? 'primary' : ($coach->speciality == 'fitness' ? 'success' : ($coach->speciality == 'finance' ? 'warning' : 'info')) }} font-20 fw-bold">
              {{ strtoupper(substr($coach->name, 0,1)) }}
            </span>
          @endif
           
        </div>
    </div>
    <div class="col ps-0">
        <h4 class="mt-1 mb-1">{{$coach->name}}</h4>
        <p class="text-muted mb-1">{{$coach->title}}</p>
        <p class="text-muted font-13 mb-0">
            <i class="mdi mdi-certificate me-1"></i>{{$coach->credentials}}
        </p>
        <p class="text-muted font-13 mb-0">
            <i class="mdi mdi-briefcase me-1"></i>{{ $coach->years_experience }} years experience
        </p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <p class="text-muted mb-2">{{$coach->description}}</p>
    </div>
</div>

<!-- Expertise Tags -->
<div class="row mt-2">
    <div class="col-12">
        <p class="text-muted mb-1 font-13">Expertise:</p> 
        @if ($coach->expertise)
            @foreach ($coach->expertise as $expertise) 

            <span class="badge bg-soft-{{ $coach->speciality == 'career' ? 'primary' : ($coach->speciality == 'fitness' ? 'success' : ($coach->speciality == 'finance' ? 'warning' : 'info')) }} me-1 mb-1">{{ $expertise }}</span>

                @endforeach
         @endif
    </div>
</div>

<!-- Stats -->
<div class="row mt-3">
    <div class="col-6">
        <p class="text-muted mb-1 font-13">Total Sessions</p>
        <h4 class="mt-0">{{$coach->total_sessions ?? 0}}</h4>
    </div>
     
</div>

<!-- Status Badge -->
<div class="row mt-3">
    <div class="col-12">
       @if ($coach->user_has_profile) 
       
        <span class="badge bg-success">
            <i class="mdi mdi-check-circle me-1"></i>Onboarding Complete
        </span>
      @else
        <span class="badge bg-warning">
            <i class="mdi mdi-alert-circle me-1"></i>Onboarding Required
        </span>
        @endif
    </div>
</div>

<!-- Action Buttons -->
<div class="row mt-3">
    <div class="col-12">
        <a href="{{ route('coaches.show',$coach->slug) }}" class="btn btn-{{ $coach->speciality == 'career' ? 'primary' : ($coach->speciality == 'fitness' ? 'success' : ($coach->speciality == 'finance' ? 'warning' : 'info')) }} w-100">
             @if ($coach->user_has_profile) 
                Start Session
            @else
                Get Started (50 tokens)
             @endif
        </a>
    </div>
</div>

                </div>
            </div>
        </div>
     @endforeach   
       
    </div>

    <!-- Info Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">How It Works</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="mdi mdi-account-plus font-32 text-primary"></i>
                                <h5 class="mt-2">1. Choose Coach</h5>
                                <p class="text-muted">Select a coach that matches your needs</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="mdi mdi-clipboard-list font-32 text-success"></i>
                                <h5 class="mt-2">2. Complete Onboarding</h5>
                                <p class="text-muted">Share your information for personalized coaching</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="mdi mdi-cash font-32 text-warning"></i>
                                <h5 class="mt-2">3. First Session</h5>
                                <p class="text-muted">Pay 50 tokens one-time to unlock the coach</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="mdi mdi-infinity font-32 text-info"></i>
                                <h5 class="mt-2">4. Unlimited Coaching</h5>
                                <p class="text-muted">All future sessions are completely FREE!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
