@extends('admin.admin_dashboard')
@section('admin')

<div class="page-container">

    <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                <h4 class="header-title">Admin Profile Page </h4>
            </div>

            <div class="card-body">
                
    <form>
        <div class="row g-2">
            <div class="mb-3 col-md-6">
                <label for="inputEmail4" class="form-label">Email</label>
                <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
            </div>
            <div class="mb-3 col-md-6">
                <label for="inputPassword4" class="form-label">Password</label>
                <input type="password" class="form-control" id="inputPassword4" placeholder="Password">
            </div>
        </div>


        

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>


</div>
@endsection