@extends('admin.admin_dashboard')

@section('admin')
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Create New Influencer</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Influencer Details</h5>
                        <a href="{{ route('admin.influencers.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body"> 

<form action="{{ route('admin.influencers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            <small class="text-muted">Example: Alex Hormozi, Elon Musk, etc.</small>
        </div>

        <div class="col-md-6 mb-3">
            <label for="niche" class="form-label">Niche</label>
            <input type="text" class="form-control" id="niche" name="niche" value="{{ old('niche') }}">
            <small class="text-muted">Example: Business, Technology, Fitness, etc.</small>
        </div>
    </div>

    <div class="mb-3">
        <label for="bio" class="form-label">Bio / Description</label>
        <textarea class="form-control" id="bio" name="bio" rows="3">{{ old('bio') }}</textarea>
        <small class="text-muted">Brief description of the influencer</small>
    </div>

    <div class="mb-3">
        <label for="style" class="form-label">Personality / Tone</label>
        <textarea class="form-control" id="style" name="style" rows="3">{{ old('style') }}</textarea>
        <small class="text-muted">Describe how this influencer speaks and behaves. Example: "Direct, no-nonsense entrepreneur who focuses on practical business advice. Uses strong analogies and real-world examples."</small>
    </div>

    <div class="mb-3">
        <label for="system_prompt" class="form-label">System Prompt (Advanced)</label>
        <textarea class="form-control" id="system_prompt" name="system_prompt" rows="4">{{ old('system_prompt') }}</textarea>
        <small class="text-muted">Custom GPT system prompt. If left blank, will auto-generate based on personality/tone.</small>
    </div>

    <div class="mb-3">
        <label for="image_prompt" class="form-label">Image Generation Prompt Template</label>
        <textarea class="form-control" id="image_prompt" name="image_prompt" rows="3">{{ old('image_prompt') }}</textarea>
        <small class="text-muted">Template for DALL-E 3 image generation. Use {scenario} as placeholder for user requests. Example: "A professional photo of a female fitness influencer named Sarah. {scenario}. High-quality, realistic, well-lit photo."</small>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="can_generate_image" name="can_generate_image" value="1" {{ old('can_generate_image', true) ? 'checked' : '' }}>
        <label class="form-check-label" for="can_generate_image">
            <strong>Enable Image Generation</strong>
            <small class="d-block text-muted">Allow users to request AI-generated photos/selfies of this influencer (10 tokens per image)</small>
        </label>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="youtube_link" class="form-label">YouTube Channel Link</label>
            <input type="url" class="form-control" id="youtube_link" name="youtube_link" value="{{ old('youtube_link') }}">
            <small class="text-muted">Optional: Link to their YouTube channel</small>
        </div>

        <div class="col-md-6 mb-3">
            <label for="avatar" class="form-label">Avatar Image</label>
            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
            <small class="text-muted">Upload a profile picture (JPG, PNG, GIF - Max 2MB)</small>
        </div>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
        <label class="form-check-label" for="is_active">
            <strong>Active</strong>
            <small class="d-block text-muted">Make this influencer visible and available for chat to users</small>
        </label>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href=" " class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="mdi mdi-content-save me-1"></i> Create Influencer
        </button>
    </div>
</form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
