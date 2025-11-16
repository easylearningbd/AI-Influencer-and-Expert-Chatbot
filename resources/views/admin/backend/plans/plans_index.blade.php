@extends('admin.admin_dashboard')
@section('admin')
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Manage Token Plans</h4>
        </div>
        <div>
            <a href=" " class="btn btn-sm btn-primary">
                <i class="mdi mdi-plus"></i> Create New Plan
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
<table class="table table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Tokens</th>
            <th>Price</th>
            <th>Type</th>
            <th>Status</th>
            <th>Featured</th>
            <th>Transactions</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($plans as $plan) 
        <tr>
            <td>
                <strong>{{ $plan->name }}</strong> 
                @if ($plan->description)
                    <br><small class="text-muted">{{ Str::limit($plan->description, 40) }}</small>
                @endif                 
            </td>
            <td>
                <span class="badge bg-info">{{ number_format($plan->tokens) }}</span>
            </td>
            <td>
                <strong>${{ number_format($plan->price) }}</strong>
                <br><small class="text-muted"> </small>
            </td>
            <td>
                <span class="badge bg-secondary">{{ ucfirst($plan->type) }}</span>
            </td>
            <td>
                @if ($plan->is_active) 
                    <span class="badge bg-success">Active</span>
                @else 
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td>
                @if ($plan->is_featured) 
                    <i class="ri-sun-fill text-warning" style="font-size: 1.2rem;"></i>
                 @else 
                    <i class="ri-sun-line text-muted"></i>
                 @endif
                 
            </td>
            <td>
                0
            </td>
            <td>
                <div class="btn-group">
                    <a href=" " class="btn btn-sm btn-primary">
                        <i class="ri-pencil-line"></i>
                    </a>
                    <form action=" " method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-{{ $plan->is_active ? 'warning' : 'success' }}"> 
                            <i class="ri-eye-{{$plan->is_active ? 'line' : 'off-line' }} "></i> 
                        </button>
                    </form>
                    <form action=" " method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this plan?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"  >
                            <i class="ri-chat-delete-line"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty 

        <tr>
            <td colspan="8" class="text-center text-muted py-4">
                <i class="mdi mdi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                <p>No plans created yet</p>
                <a href=" " class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Create First Plan
                </a>
            </td>
        </tr>
       @endforelse
       
    </tbody>
</table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
