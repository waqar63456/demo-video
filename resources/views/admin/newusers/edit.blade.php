@extends('admin.layout.main')
@section('content')
    <div class="content bg-light m-3 bg-white rounded">
        <div class="row">
            <div class="col-md-10">
                <h4 class="content-heading">Edit New user</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8 col-xl-8">
                <form class="mb-5" action="{{ route('newusers.update', $newusers->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    {{-- name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name"
                            value="{{ $newusers->name }}" required>
                    </div>
                    {{-- email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email"
                            value="{{ $newusers->email }}" required>
                    </div>
                    {{-- phone --}}
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone"
                            value="{{ $newusers->phone }}" required>
                    </div>
                    {{-- password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Enter Password">
                        <small class="form-text text-muted">Leave blank to keep current password</small>
                    </div>
                    {{-- company_name --}}

                    {{-- <div class="mb-3">
                        <label for="user_type" class="form-label">User Type</label>
                        <select class="form-control" id="user_type" name="user_type" required>
                            <option value="" disabled>Select User Type</option>
                            <option value="earner" {{ $newusers->user_type == 'earner' ? 'selected' : '' }}>Earner</option>
                            <option value="seller" {{ $newusers->user_type == 'seller' ? 'selected' : '' }}>Seller</option>
                        </select>
                    </div> --}}
                    <div class="mb-3">
                        <label for="is_active" class="form-label">User Status</label>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="1" {{ $newusers->is_active == 1 ? 'selected' : '' }}>Activate</option>
                            <option value="0" {{ $newusers->is_active == 0 ? 'selected' : '' }}>Deactivate</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
@endsection
