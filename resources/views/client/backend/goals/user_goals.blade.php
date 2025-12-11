@extends('client.client_dashboard')
@section('client') 
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Your Goals with {{ $coach->name }} </h4>
        </div>
        <div class="text-sm-end">
            <a href="{{ route('coaches.show',$coach->slug) }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-1"></i> Back to Chat
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted fw-normal mt-0">In Progress</h5>
                    <h3 class="my-2 py-1">{{ $inProgress->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted fw-normal mt-0">Completed</h5>
                    <h3 class="my-2 py-1">{{ $completed->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted fw-normal mt-0">Total Goals</h5>
                    <h3 class="my-2 py-1">{{ $goals->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Create New Goal Button -->
    <div class="row mb-3">
        <div class="col-12">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGoalModal">
                <i class="mdi mdi-plus me-1"></i> Create New Goal
            </button>
        </div>
    </div>

    <!-- In Progress Goals -->
    @if ($inProgress->count() > 0) 
    <h4 class="mb-3">In Progress</h4>
    <div class="row mb-4">
        @foreach ($inProgress as $goal) 
        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $goal->title }}</h5>
                     @if ($goal->description) 
                    <p class="text-muted">{{ Str::limit($goal->description, 100)  }}</p>
                      @endif

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Progress</span>
                            <span class="text-muted small">{{ $goal->progress_percentage }} %</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $goal->progress_percentage }}%"></div>
                        </div>
                    </div>

                    @if ($goal->target_date) 
                    <p class="text-muted mb-2">
                        <i class="mdi mdi-calendar me-1"></i>
                        Target: {{ $goal->target_date->format('M d, Y') }}
                    </p>
                    @endif

                @if ($goal->priority)
                <span class="badge bg-{{ $goal->priority == 'high' ? 'danger' : ($goal->priority == 'medium' ? 'warning' : 'info') }} mb-2">{{ ucfirst($goal->priority) }} Priority</span> 
                @endif

    <!--- Action Button  -->
    <div class="d-flex gap-2 mt-3">
        <button class="btn btn-sm btn-primary" onclick="openPrgoressModal({{ $goal->id }}, '{{ $goal->title }}' , {{ $goal->progress_percentage }})">Progress</button>

        <button class="btn btn-sm btn-info" onclick="openEditModal({{ $goal->id }}, '{{ $goal->title }}','{{ ($goal->description ?? '')}}', '{{ $goal->category ?? '' }}', '{{ $goal->priority }}' ,'{{ $goal->target_date?->format('Y-m-d') ?? '' }}' )">Edit</button>

        <form action="">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
        </form>

    </div>

                    
                </div>
            </div>
        </div>
      @endforeach
    </div>
    @endif

    <!-- Completed Goals -->
   @if ($completed->count() > 0) 
    <h4 class="mb-3">Completed Goals</h4>
    <div class="row">
       @foreach ($completed as $goal) 
        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title">{{ $goal->title }}</h5>
                        <i class="mdi mdi-check-circle text-success font-20"></i>
                    </div>
                    
                    @if ($goal->description) 
                    <p class="text-muted">{{ Str::limit($goal->description, 100)  }}</p>
                      @endif
                    
                    <p class="text-muted mb-0">
                        <i class="mdi mdi-calendar-check me-1"></i>
                        Completed: {{ $goal->completed_at->format('M d,Y') }}
                    </p>
                </div>
            </div>
        </div>
         @endforeach
    </div>
   @endif

    @if ($goals->count() == 0)  
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-target font-48 text-muted mb-3"></i>
                    <h4>No goals yet</h4>
                    <p class="text-muted">Start by creating your first goal with {{ $coach->name }}</p>
                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#createGoalModal">
                        <i class="mdi mdi-plus me-1"></i> Create Your First Goal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Create Goal Modal -->
<div class="modal fade" id="createGoalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('coaches.goals.store',$coach->slug) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create New Goal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Goal Title *</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="category" name="category">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="target_date" class="form-label">Target Date</label>
                        <input type="date" class="form-control" id="target_date" name="target_date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Goal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
