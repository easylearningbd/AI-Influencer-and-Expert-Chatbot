@extends('admin.admin_dashboard')
@section('admin')

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">View Influencer: {{ $influencer->name }}  </h4>
        </div>
    </div>

<div class="row">
<div class="col-md-4">
    <div class="card">
        <div class="card-body text-center">
            @if ($influencer->avatar) 
                <img src="{{ asset($influencer->avatar) }}" alt=" " class="rounded-circle mb-3" width="150" height="150">
            @else
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px; font-size: 4rem;">
                {{ substr($influencer->name, 0,1) }}
                </div>
             @endif

            <h4>{{ $influencer->name }}</h4>
            
                <span class="badge bg-info mb-2">{{ $influencer->niche }}</span>
            

            <p class="text-muted">{{ $influencer->slug }}</p>

            <div class="d-flex justify-content-around my-3 py-3 border-top border-bottom">
                <div>
                    <h5 class="mb-0">{{ $influencer->chat_count }}</h5>
                    <small class="text-muted">Chats</small>
                </div>
                <div>
                    <h5 class="mb-0">{{ $influencer->influencerData->count() }}</h5>
                    <small class="text-muted">Data Sources</small>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('admin.influencers.edit',$influencer->id) }}" class="btn btn-primary">
                    <i class="mdi mdi-pencil me-1"></i> Edit Influencer
                </a>
                <a href="{{ route('admin.influencers.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>

<div class="col-md-8">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Influencer Information</h5>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th width="30%">Status:</th>
                    <td>
                        @if ($influencer->is_active) 
                            <span class="badge bg-success">Active</span>
                        @else 
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Bio:</th>
                    <td>{{ $influencer->bio ?? 'Not Provided' }}</td>
                </tr>
                <tr>
                    <th>Personality/Tone:</th>
                    <td>{{ $influencer->style ?? 'Not Provided' }}</td>
                </tr>
                <tr>
                    <th>YouTube Channel:</th>
                    <td>
                         @if ($influencer->youtube_link) 
                         <a href="{{ $influencer->youtube_link }}" target="_blank" >{{ $influencer->youtube_link }}</a>
                         @else 
                            Not provided
                         @endif
                    </td>
                </tr>
                <tr>
                    <th>Created:</th>
                    <td>{{ $influencer->created_at->format('M d, Y h:i A') }}</td>
                </tr>
                <tr>
                    <th>Last Updated:</th>
                    <td>{{ $influencer->updated_at->format('M d, Y h:i A') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Recent Chats -->
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title mb-0">Recent Chat History</h5>
        </div>
        <div class="card-body">
            @if ($influencer->chats->count() > 0 ) 
                <div class="list-group">
                   @foreach ($influencer->chats as $chat) 
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $chat->user->name }}</strong>
                            <small class="text-muted">{{ $chat->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 mt-2"><strong>User:</strong> {{ Str::limit($chat->message, 100) }} </p>
                        <p class="mb-1 text-muted"><strong>Response:</strong> {{ Str::limit($chat->response, 100) }} </p>
                        <small class="text-muted">Tokens used: {{ $chat->tokens_used }} </small>
                    </div>
                   @endforeach 
                    
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="mdi mdi-information"></i> No chat history yet.
                </div>
             @endif
        </div>
    </div>
</div>
    </div>

</div>
@endsection
