@extends('admin.layout.main')
@section('content')
<div class="content bg-light m-3 bg-white rounded">
    <div class="row">
        <div class="col-md-10">
            <h4 class="content-heading">Add Newusers</h4>
        </div>
        <div class="col-md-2 float-right pt-3">
            <a href="{{ route('newusers.index') }}" class="btn btn-primary float-right">All users</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8 col-xl-8">
            <form class="mb-5" action="{{ route('newusers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- name --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                </div>
                {{-- email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                </div>
                {{-- phone --}}
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone" required>
                </div>
                {{-- password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                </div>
                 {{-- company_name --}}
              
                 <div class="mb-3">
                    <label for="s_active" class="form-label">User Status</label>
                    <select class="form-control" id="is_active" name="is_active">
                        <option value="1" >activate</option>
                        <option value="0" >deactive</option>
                    </select>
                </div>
              

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Add newusers</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
