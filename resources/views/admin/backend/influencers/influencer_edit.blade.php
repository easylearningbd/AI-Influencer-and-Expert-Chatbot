@extends('admin.admin_dashboard')

@section('admin')
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Edit Influencer: {{ $influencer->name }} </h4>
        </div>
    </div>

    <div class="row">
        <!-- Influencer Details Form -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Influencer Details</h5>
                        <a href="{{ route('admin.influencers.index') }}" class="btn btn-sm btn-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body"> 

<form action=" " method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3 text-center">
            @if ($influencer->avatar) 
            <img src="{{ asset($influencer->avatar) }}" alt=" " class="rounded-circle mb-2" width="100" height="100">
            @else
            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 100px; height: 100px; font-size: 2rem;">
               {{ substr($influencer->name, 0,1) }}
            </div>
            @endif
        
    </div>

    <div class="mb-3">
        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name',  ) }}" required>
    </div>

    <div class="mb-3">
        <label for="niche" class="form-label">Niche</label>
        <input type="text" class="form-control" id="niche" name="niche" value="{{ old('niche',  ) }}">
    </div>

    <div class="mb-3">
        <label for="bio" class="form-label">Bio / Description</label>
        <textarea class="form-control" id="bio" name="bio" rows="3">{{ old('bio', ) }}</textarea>
    </div>

    <div class="mb-3">
        <label for="style" class="form-label">Personality / Tone</label>
        <textarea class="form-control" id="style" name="style" rows="3">{{ old('style',  ) }}</textarea>
    </div>

    <div class="mb-3">
        <label for="system_prompt" class="form-label">System Prompt</label>
        <textarea class="form-control" id="system_prompt" name="system_prompt" rows="4">{{ old('system_prompt',  ) }}</textarea>
    </div>

    <div class="mb-3">
        <label for="image_prompt" class="form-label">Image Generation Prompt Template</label>
        <textarea class="form-control" id="image_prompt" name="image_prompt" rows="3">{{ old('image_prompt',  ) }}</textarea>
        <small class="text-muted">Template for DALL-E 3 image generation. Use {scenario} as placeholder for user requests.</small>
    </div>

    <div class="mb-3">
        <label for="youtube_link" class="form-label">YouTube Channel Link</label>
        <input type="url" class="form-control" id="youtube_link" name="youtube_link" value="{{ old('youtube_link', ) }}">
    </div>

    <div class="mb-3">
        <label for="avatar" class="form-label">Change Avatar</label>
        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="can_generate_images" name="can_generate_images" value="1" {{ old('can_generate_images' ) ? 'checked' : '' }}>
        <label class="form-check-label" for="can_generate_images">
            <strong>Enable Image Generation</strong>
            <small class="d-block text-muted">Allow users to request AI-generated photos/selfies (10 tokens per image)</small>
        </label>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', ) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">
            <strong>Active</strong>
            <small class="d-block text-muted">Make this influencer visible and available for chat to users</small>
        </label>
    </div>

    <button type="submit" class="btn btn-primary w-100">
        <i class="mdi mdi-content-save me-1"></i> Update Influencer
    </button>
</form>
                </div>
            </div>
        </div>

        <!-- Training Data Management -->
<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Training Data</h5>
        </div>

<div class="card-body">
    <!-- Upload File Tab -->
    <ul class="nav nav-tabs mb-3" id="trainingDataTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="file-tab" data-bs-toggle="tab" data-bs-target="#file-upload" type="button">
                <i class="mdi mdi-file-upload"></i> Upload File
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="youtube-tab" data-bs-toggle="tab" data-bs-target="#youtube-transcript" type="button">
                <i class="mdi mdi-youtube"></i> YouTube
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="text-tab" data-bs-toggle="tab" data-bs-target="#manual-text" type="button">
                <i class="mdi mdi-text"></i> Manual Text
            </button>
        </li>
    </ul>

    <div class="tab-content" id="trainingDataTabContent">
        <!-- File Upload -->
        <div class="tab-pane fade show active" id="file-upload" role="tabpanel">
            <form action=" " method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">Upload PDF or TXT File</label>
                    <input type="file" class="form-control" id="file" name="file" accept=".pdf,.txt" required>
                    <small class="text-muted">Max size: 10MB</small>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-upload me-1"></i> Upload File
                </button>
            </form>
        </div>

        <!-- YouTube Transcript -->
        <div class="tab-pane fade" id="youtube-transcript" role="tabpanel">
            <form action=" " method="POST">
                @csrf
                <div class="mb-3">
                    <label for="youtube_url" class="form-label">YouTube Video URL</label>
                    <input type="url" class="form-control" id="youtube_url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=..." required>
                    <small class="text-muted">We'll extract the transcript automatically</small>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-download me-1"></i> Fetch Transcript
                </button>
            </form>
        </div>

        <!-- Manual Text -->
        <div class="tab-pane fade" id="manual-text" role="tabpanel">
            <form action=" " method="POST">
                @csrf
                <div class="mb-3">
                    <label for="content" class="form-label">Paste Text Content</label>
                    <textarea class="form-control" id="content" name="content" rows="6" placeholder="Enter training content here..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-plus me-1"></i> Add Text
                </button>
            </form>
        </div>
    </div>

    <hr class="my-4">

    <!-- Training Data List -->
    <h6 class="mb-3">Uploaded Training Data (2)</h6>

    
        <div class="list-group">
            
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        
                            <i class="ri-file-pdf-2-line text-danger"></i>
                        
                            <i class="ri-file-paper-2-line text-info"></i>
                        
                            <i class="ri-youtube-line text-danger"></i>
                            

                        <strong>
                              
                                Manual Text Entry
                            
                        </strong>
                        <br>
                        <small class="text-muted">
                            chunk_size characters |
                            Added created_at
                        </small>
                    </div>
                    <form action=" " method="POST" onsubmit="return confirm('Delete this training data?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="ri-chat-delete-line"></i>
                        </button>
                    </form>
                </div>
            </div>
            
        </div>
    
        <div class="alert alert-info">
            <i class="mdi mdi-information"></i> No training data uploaded yet. Add some content to train the AI.
        </div>
        
</div>
    </div>

            <!-- Stats Card -->
<div class="card mt-3">
    <div class="card-body">
        <h6 class="card-title">Statistics</h6>
        <div class="row text-center">
            <div class="col-4">
                <h4 class="text-primary">chat_count</h4>
                <small class="text-muted">Total Chats</small>
            </div>
            <div class="col-4">
                <h4 class="text-info">count</h4>
                <small class="text-muted">Data Sources</small>
            </div>
            <div class="col-4">
                <h4 class="text-success">chunk_size</h4>
                <small class="text-muted">Total Chars</small>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>

</div>
@endsection
