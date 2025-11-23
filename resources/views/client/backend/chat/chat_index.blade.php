@extends('client.client_dashboard')
@section('client')
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Chat with AI Influencers</h4>
        </div>
    </div> 

    <!-- User Token Balance -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-wallet"></i>
                        <strong>Your Token Balance:</strong> {{ auth()->user()->token_balance }} tokens
                        <span class="text-muted ms-2">( {{ env('CHAT_TOKENS_PER_MESSAGE',5) }} tokens per message)</span>
                    </div>
                    <a href="#" class="btn btn-sm btn-primary">Buy More Tokens</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Influencer Grid -->
    <div class="row">
      
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card influencer-card h-100">
                <div class="card-body text-center">
                   
                        <img src=" "  class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
                    
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                          name
                        </div>
                   

                    <h5 class="card-title mb-2">name</h5>

                  
                        <span class="badge bg-info mb-2">niche</span>
                   

                    <p class="text-muted small mb-3">bio</p>

                    <div class="d-flex justify-content-around text-muted small mb-3">
                        <div>
                            <i class="mdi mdi-message"></i>
                            <span>  chats</span>
                        </div>
                        <div>
                            <i class="mdi mdi-database"></i>
                            <span> sources</span>
                        </div>
                    </div>

                    <a href=" " class="btn btn-primary w-100">
                        <i class="mdi mdi-chat"></i> Start Chat
                    </a>
                </div>
            </div>
        </div>
       
        <div class="col-12">
            <div class="alert alert-warning text-center">
                <i class="mdi mdi-information"></i> No influencers available at the moment. Please check back later!
            </div>
        </div>
         
    </div>

</div>

<style>
.influencer-card {
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}

.influencer-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>
@endsection
