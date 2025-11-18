@extends('admin.admin_dashboard')
@section('admin')

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Manage Influencers</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">All Influencers</h5>
                        <a href="{{ route('admin.influencers.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus-circle me-1"></i> Add New Influencer
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('message'))
                        <div class="alert alert-{{ session('alert-type') }} alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

<div class="table-responsive">
    <table class="table table-striped table-hover mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Avatar</th>
                <th>Name</th>
                <th>Niche</th>
                <th>Training Data</th>
                <th>Total Chats</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
 @forelse ($influencers as $key => $influncer) 
<tr>
    <td>{{ $key + 1 }}</td>
    <td>
        @if ($influncer->avatar) 
            <img src="{{ asset($influncer->avatar) }}" alt="{{ $influncer->name }}" class="rounded-circle" width="40" height="40">
        @else    
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
               {{ substr($influncer->name, 0, 1) }}  
            </div>
         @endif
    </td>
    <td>
        <strong>{{ $influncer->name }}</strong><br>
        <small class="text-muted">{{ $influncer->slug }}</small>
    </td>
    <td>
           @if ($influncer->niche) 
            <span class="badge bg-info"> {{ $influncer->niche }}</span>
           @else
            <span class="text-muted">-</span>
           @endif
    </td>
    <td>
        <span class="badge bg-secondary"> {{ $influncer->influencerData->count() }} Items</span>
    </td>
    <td>
        <span class="badge bg-primary">{{ $influncer->chat_count }}</span>
    </td>
    <td>
            @if ($influncer->is_active) 
            <span class="badge bg-success">Active</span>
            @else
            <span class="badge bg-danger">Inactive</span>
            @endif
    </td>
    <td>
        <div class="btn-group" role="group">
            <a href=" " class="btn btn-sm btn-primary" title="Edit">
                <i class="ri-pencil-line"></i>
            </a>
            <a href=" " class="btn btn-sm btn-info" title="View">
                <i class="ri-focus-3-line"></i>
            </a>
            <form action=" " method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-warning" title="Toggle Status">
                    <i class="ri-arrow-left-right-line"></i>
                </button>
            </form>
            <form action=" " method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this influencer?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                    <i class="ri-chat-delete-line"></i>
                </button>
            </form>
        </div>
    </td>
</tr>

@empty 
                             
            <tr>
                <td colspan="8" class="text-center py-4">
                    <p class="text-muted mb-0">No influencers found.
    <a href="{{ route('admin.influencers.create') }}" class="btn btn-primary">
<i class="mdi mdi-plus"></i> Create your first influencer
</a>  
                        
                </td>
            </tr>
  @endforelse 
                               
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $influencers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
