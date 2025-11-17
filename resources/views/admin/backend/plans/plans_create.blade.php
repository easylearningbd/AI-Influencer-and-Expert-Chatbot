@extends('admin.admin_dashboard')

@section('admin')
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">
                <a href=" " class="text-muted"><i class="mdi mdi-arrow-left"></i></a>
                Create New Plan
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
<form action="{{ route('admin.plans.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Plan Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="tokens" class="form-label">Number of Tokens <span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('tokens') is-invalid @enderror" id="tokens" name="tokens" value="{{ old('tokens') }}" min="1" required>
            @error('tokens')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="type" class="form-label">Plan Type <span class="text-danger">*</span></label>
            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                <option value="one-time" {{ old('type') === 'one-time' ? 'selected' : '' }}>One-Time</option>
                <option value="monthly" {{ old('type') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="yearly" {{ old('type') === 'yearly' ? 'selected' : '' }}>Yearly</option>
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="sort_order" class="form-label">Sort Order</label>
            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
            @error('sort_order')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Lower numbers appear first</small>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Features</label>
        <div id="features-container">
            <div class="input-group mb-2">
                <input type="text" class="form-control" name="features[]" placeholder="e.g., Enter Features">
                <button type="button" class="btn btn-danger remove-feature" style="display: none;">
                    <i class="ri-file-reduce-line"></i>
                </button>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" id="add-feature">
            <i class="ri-file-add-line"> </i> Add Feature
        </button>
    </div>

    <div class="mb-3">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                Active (visible to users)
            </label>
        </div>
    </div>

    <div class="mb-3">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
            <label class="form-check-label" for="is_featured">
                Featured Plan (Most Popular badge)
            </label>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="mdi mdi-check"></i> Create Plan
        </button>
    </div>
</form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">Plan Tips</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0 ps-3">
                        <li class="mb-2"><strong>Name:</strong> Keep it short and memorable (e.g., "Starter", "Pro", "Premium")</li>
                        <li class="mb-2"><strong>Tokens:</strong> Common amounts: 100, 300, 500, 1000</li>
                        <li class="mb-2"><strong>Pricing:</strong> Consider price per token for better value at higher tiers</li>
                        <li class="mb-2"><strong>Features:</strong> List what makes this plan special</li>
                        <li class="mb-2"><strong>Featured:</strong> Only one plan should be featured as "Most Popular"</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>



<script>
document.addEventListener('DOMContentLoaded', function(){
    const featuresContainer = document.getElementById('features-container');
    const addFeatureBtn = document.getElementById('add-feature');

    /// Add New Feature field 
    addFeatureBtn.addEventListener('click', function(){
        const newField = document.createElement('div');
        newField.className = 'input-group mb-2';
        newField.innerHTML = `
        <input type="text" class="form-control" name="features[]" placeholder="Enter Features">
        <button type="button" class="btn btn-danger remove-feature">
            <i class="ri-file-reduce-line"></i>
        </button>        
        `;
        featuresContainer.appendChild(newField);
        updateRemoveButtons();
    });

    // Remove feature field 
    featuresContainer.addEventListener('click', function(e){
        if (e.target.closest('.remove-feature')) {
            e.target.closest('.input-group').remove();
            updateRemoveButtons();
        }
    });

    /// Update remove button visibility 

    function updateRemoveButtons(){
        const fields = featuresContainer.querySelectorAll('.input-group');
        fields.forEach((field, index) => {
            const removeBtn = field.querySelector('.remove-feature');
            if (fields.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                 removeBtn.style.display = 'none';
            }
        });
    }  

    updateRemoveButtons();

 }); 

</script>

 
@endsection
