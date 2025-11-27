@extends('client.client_dashboard')
@section('client')
<div class="container-xxl">

 <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">
            <a href="{{ route('user.chat.index') }}" class="text-muted"><i class="mdi mdi-arrow-left"></i></a>
            Chat with {{ $influencer->name }}
        </h4>
    </div>
    <div>
        <span class="badge bg-info me-2">
            <i class="mdi mdi-wallet"></i> {{ auth()->user()->token_balance }} tokens
        </span>
        @if ($sessionId && count($chatHistory) > 0) 
        <div class="btn-group me-2" role="group">
            <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-download"></i> Export (5 tokens)
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href=" ">
                        <i class="mdi mdi-file-pdf-box"></i> Export as PDF
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href=" ">
                        <i class="mdi mdi-file-document-outline"></i> Export as Text
                    </a>
                </li>
            </ul>
        </div>
        @endif
        <a href="{{ route('user.chat.new-session',$influencer->slug) }}" class="btn btn-sm btn-primary">
            <i class="mdi mdi-plus"></i> New Chat
        </a>
    </div>
</div>

    <div class="row">
        <!-- Chat Sessions Sidebar -->
<div class="col-md-3">
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Chat History</h6>
        </div>
        <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
            <div class="list-group list-group-flush">
            @forelse ($sessions as $session) 
                <a href="{{ route('user.chat.show',['influencer' => $influencer->slug , 'session_id' => $session->session_id]) }}"
                    class="list-group-item list-group-item-action {{ $sessionId == $session->session_id ? 'active' : '' }}  ">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
<small class="text-muted">{{ $session->started_at->format('M d, Y') }}</small>
<br>
<small>{{ $session->message_count }} messages</small>
                        </div>
                        <i class="mdi mdi-chevron-right"></i>
                    </div>
                </a>
            @empty
                <div class="list-group-item text-center text-muted">
                    <small>No chat history yet</small>
                </div>
            @endforelse   
            </div>
        </div>
    </div>

    <!-- Influencer Info Card -->
    <div class="card mt-3">
        <div class="card-body text-center">
            @if ($influencer->avatar) 
                <img src="{{ asset($influencer->avatar) }}" alt=" " class="rounded-circle mb-2" width="80" height="80">
            @else 
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($influencer->name, 0,1) }}
                </div>
             @endif
            <h6>{{ $influencer->name }}</h6>
            @if ($influencer->niche) 
                <span class="badge bg-info">{{ $influencer->niche }}</span>
            @endif
            <p class="text-muted small mt-2 mb-0">{{ Str::limit($influencer->bio, 100) }}</p>
        </div>
    </div>
</div>

        <!-- Chat Interface -->
<div class="col-md-9">
    <div class="card">
        <div class="card-body p-0" style="height: 600px; display: flex; flex-direction: column;">
            <!-- Chat Messages -->
    <div id="chat-messages" class="flex-grow-1 p-3" style="overflow-y: auto; background-color: #f8f9fa;">
    
            
            <!-- User Message -->
            <div class="d-flex justify-content-end mb-3">
                <div class="bg-primary text-white rounded p-2 px-3" style="max-width: 70%;">
                    message
                    <div class="text-end">
                        <small style="opacity: 0.8;">created_at</small>
                    </div>
                </div>
            </div>

            <!-- AI Response -->
            <div class="d-flex justify-content-start mb-3">
                <div>
                    
                        <img src=" " alt=" " class="rounded-circle me-2" width="40" height="40">
                    
                        <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                            dd
                        </div>
                    
                </div>
                <div class="bg-white rounded p-2 px-3 border" style="max-width: 70%;">
                    
                        <p class="mb-2 small text-muted">Here's the image you requested!</p>
                        <img src=" " alt="Generated image" class="img-fluid rounded" style="max-width: 100%; cursor: pointer;" onclick="window.open('', '_blank')">
                    
                        response
                    
                    <div class="text-end  ">
                        created_at
                    </div>
                </div>
            </div>
            
            <div class="text-center text-muted mt-5">
                <i class="mdi mdi-chat-outline" style="font-size: 4rem;"></i>
                <p>Start a conversation with name</p>
                <p class="small">They'll respond in their authentic style based on their training data.</p>
            </div>
        

        <!-- Typing Indicator -->
        <div id="typing-indicator" class="d-none mb-3">
            <div class="d-flex justify-content-start">
                <div>
                    
                        <img src=" " alt=" " class="rounded-circle me-2" width="40" height="40">
                    
                        <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                            4
                        </div>
                    
                </div>
                <div class="bg-white rounded p-2 px-3 border">
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

                    <!-- Message Input -->
  <div class="border-top p-3 bg-white">
<form id="chat-form">
    @csrf
    <input type="hidden" id="session-id" value=" ">

    <!-- Language Selector -->
    <div class="mb-2">
        <label for="language-select" class="form-label small mb-1">
            <i class="mdi mdi-translate"></i> Response Language
        </label>
        <select id="language-select" class="form-select form-select-sm" style="max-width: 200px;">
            <option value="en">English</option>
            <option value="es">Spanish</option>
            <option value="fr">French</option>
            <option value="de">German</option>
            <option value="it">Italian</option>
            <option value="pt">Portuguese</option>
            <option value="ru">Russian</option>
            <option value="zh">Chinese</option>
            <option value="ja">Japanese</option>
            <option value="ko">Korean</option>
            <option value="ar">Arabic</option>
            <option value="hi">Hindi</option>
            <option value="bn">Bengali</option>
            <option value="nl">Dutch</option>
            <option value="tr">Turkish</option>
            <option value="pl">Polish</option>
            <option value="vi">Vietnamese</option>
            <option value="th">Thai</option>
            <option value="id">Indonesian</option>
            <option value="uk">Ukrainian</option>
        </select>
    </div>

    <div class="input-group">
        <input type="text" id="message-input" class="form-control" placeholder="Type your message..." autocomplete="off" required>
        
        <button type="button" class="btn btn-success" id="image-request-btn" title="Request an image (10 tokens)">
            <i class="ri-file-image-line"></i>
        </button>
        
        <button type="submit" class="btn btn-primary" id="send-btn">
            <i class="ri-send-plane-fill"> </i> Send
        </button>
    </div>
    <div id="error-message" class="text-danger small mt-2 d-none"></div>
    <small class="text-muted d-block mt-1">
        
        <i class="mdi mdi-lightbulb-outline"></i> Tip: Click the image icon to request a photo/selfie (10 tokens). Example: "Send me a photo of you at the beach"
        
    </small>
</form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
#chat-messages::-webkit-scrollbar {
    width: 8px;
}

#chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#chat-messages::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

#chat-messages::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.typing-dots {
    display: inline-flex;
    align-items: center;
}

.typing-dots span {
    display: inline-block;
    width: 8px;
    height: 8px;
    margin: 0 2px;
    background-color: #999;
    border-radius: 50%;
    animation: typing 1.4s infinite;
}

.typing-dots span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dots span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.7;
    }
    30% {
        transform: translateY(-10px);
        opacity: 1;
    }
}
</style>

 
@endsection
