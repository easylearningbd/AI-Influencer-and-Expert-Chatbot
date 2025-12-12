@extends('client.client_dashboard')
@section('client') 
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">{{ $coach->name }}</h4>
            <p class="text-muted">{{ $coach->title }}</p>
        </div>
        <div class="text-sm-end">
            <a href="{{ route('all.coaches') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-1"></i> Back to Coaches
            </a>
        </div>
    </div> 
 

   @if ($needsOnboarding) 
    <!-- Onboarding Required -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="avatar-lg mx-auto mb-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-24 fw-bold">
                           {{ strtoupper(substr($coach->name, 0, 1)) }}
                        </span>
                    </div>
                    <h3>{{$coach->name}}</h3>
                    <p class="text-muted mb-3">{{$coach->description}}</p>
                    <div class="alert alert-warning d-inline-block">
                        <i class="mdi mdi-alert-circle me-2"></i>Please complete onboarding before starting your coaching session
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('coaches.onborarding',$coach->slug) }}" class="btn btn-primary btn-lg">
                            Complete Onboarding (50 tokens)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else 
    <!-- Chat Interface -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <div class="avatar-md me-3">
                <span class="avatar-title rounded-circle bg-soft-primary text-primary font-20 fw-bold">
                    {{ strtoupper(substr($coach->name, 0, 1)) }}
                </span>
            </div>
            <div>
                <h4 class="mb-0">{{ $coach->name }}</h4>
                <p class="text-muted mb-0">{{ $coach->title }}</p>
            </div>
            @if ($activeSession) 
            <span class="badge bg-success ms-auto">
                <i class="mdi mdi-circle me-1"></i>Active Session
            </span>
            @endif
            
        </div>

          @if ($isFirstSession) 
        <div class="alert alert-info">
            <i class="mdi mdi-information me-2"></i>
            <strong>First Session:</strong> This session will cost 50 tokens. All future sessions with {{ $coach->name }} will be FREE!
        </div>
        @endif
        

        <div id="chat-container" style="height: 600px; overflow-y: auto; background: #f8f9fa; border-radius: 8px; padding: 20px;">
            <div class="text-center text-muted py-5" id="empty-state">
                <i class="mdi mdi-chat-outline font-48 mb-3"></i>
                <p>Start your coaching session below</p>
            </div>
        </div>

        <div class="mt-3">
            <form id="message-form">
                @csrf
                <div class="input-group">
                    <input type="text" id="message-input" class="form-control" placeholder="Type your message..." required>
                    <button class="btn btn-primary" type="submit" id="send-btn">
                        <i class="mdi mdi-send"></i> Send
                    </button>
                </div>
            </form>
            <div class="text-muted mt-2 small">
                <span id="status-text">Ready to chat</span>
            </div>
        </div>
    </div>
</div>
</div>

<div class="col-lg-4">
<!-- Coach Info -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-3">Coach Information</h5>
        <p><strong>Credentials:</strong> {{ $coach->credentials }}</p>
        <p><strong>Experience:</strong> {{ $coach->years_experience }} years</p>
        <p><strong>Expertise:</strong></p>
        <div>
            @foreach ($coach->expertise  ?? [] as $exp) 
            <span class="badge bg-soft-primary text-primary me-1 mb-1">{{$exp}}</span>
           @endforeach
            
        </div>
        <hr>
        @if ($profile) 
        <a href=" " class="btn btn-sm btn-outline-primary">Update Profile</a>
        @endif  
    </div>
</div>

<!-- Goals -->
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Your Goals</h5>
            <a href="{{ route('coaches.goals.index',$coach->slug) }}" class="btn btn-sm btn-primary">
                <i class="ri-file-add-fill"></i>
            </a>
        </div>
        @if ($goals->count() > 0) 
             @foreach ($goals->take(3) as $goal) 
            <div class="mb-3">
                <h6 class="mb-1">{{ $goal->title }}</h6>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $goal->progress_percentage }}%"></div>
                </div>
                <small class="text-muted">{{ $goal->progress_percentage }}% complete</small>
            </div>
            @endforeach
            <a href="{{ route('coaches.goals.index',$coach->slug) }}" class="btn btn-sm btn-outline-secondary w-100">View All Goals</a>
        @else
        <p class="text-muted text-center py-3">No goals yet</p>
        @endif
    </div>
            </div>
        </div>
    </div>
   @endif

</div>

@if (!$needsOnboarding) 
<script>
let sessionId = null;
const coachSlug = '{{ $coach->slug }}';
const chatContainer = document.getElementById('chat-container');
const messageForm = document.getElementById('message-form');
const messageInput = document.getElementById('message-input');
const sendBtn = document.getElementById('send-btn');
const statusText = document.getElementById('status-text');

document.addEventListener('DOMContentLoaded', startSession);

async function startSession(){
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF Token not found');
            statusText.textContent = 'Failed to start session token missing';
            return;
        }

    const response = await fetch(`/coaches/${coachSlug}/start-session`,{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content
        }
    });

    if (!response.ok) {
        const errorText = await response.text();
        console.error('Server error:',errorText);
        statusText.textContent = 'Failed to start session';
        return;
    }

    const data = await response.json();

    if (!data.error) { 
            console.error('Server error:',data.error);
            statusText.textContent = `Error: ${data.error}`;
            return;
        }

     sessionId = data.session.session_id;
     statusText.textContent = data.message;
     loadMessages(); 

    } catch (error) {
        console.error('Error starting session:',error);
    } 
}
/// End startSession Method 

async function loadMessages(){

}




 

</script>
@endif
 
@endsection
