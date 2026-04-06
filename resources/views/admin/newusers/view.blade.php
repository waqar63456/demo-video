@extends('admin.layout.main')
@section('content')
<div class="content bg-light m-3 bg-white rounded">
    <div class="row content-heading">
        <div class="col-md-10">
            <h4 class="mb-0">Users</h4>
        </div>
        <div class="col-md-2 float-right ">
            <a href="{{ route('newusers.create') }}" class="btn btn-primary float-right">Add Users</a>
        </div>
        <div class="mb-3 mt-3">
            <form action="{{ route('newusers.index') }}" method="GET" class="w-100">
                <div class="row g-2 ms-3">
                    <div class="col-12 col-sm-6 col-md-5 col-lg-10 mb-2">
                        <select class="form-control form-control-sm text-dark" id="userSearch" name="name"
                            style="height: 34px; background-color: #F9F9F9; width: 100%;">

                            <option value=""></option>
                            @foreach ($allUsers as $user)
                                <option value="{{ $user->id }}"
                                    {{ request('name') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-12 col-md-2 col-lg-2 d-flex align-items-center mb-2 vehicles_btn_group">
                        <button type="submit" class="btn  btn-info" style="border-radius: 6px !important">
                            <i class="fa fa-search text-light"></i>
                        </button>
                        <button type="submit" class="btn  btn-info ms-1" style="border-radius: 6px !important"
                            name="name" value="all">
                            <i class="fa-solid fa-broom text-light"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <script>
            jQuery.noConflict();
            jQuery(document).ready(function($) {
                console.log("jQuery version:", $.fn.jquery);
                console.log("Select2 available:", typeof $.fn.select2 !== 'undefined');

                if (typeof $.fn.select2 !== 'undefined') {
                    $('#userSearch').select2({
                        placeholder: 'Search By Name',
                        allowClear: true,
                        ajax: {
                            url: '{{ route('user.search') }}',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    search: params.term
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.map(user => ({
                                        id: user.id,
                                        text: user.name
                                    }))
                                };
                            }
                        }
                    });
                } else {
                    console.error("Select2 is not loaded!");
                }
                $('.select2-selection').css({
                    'height': '34px',
                    'padding-top': '0.5px',
                    'background-color': '#F9F9F9',
                });
                $('.select2-selection__arrow').css({
                    'padding': '4px !important'
                });
            });
        </script>
    </div>
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        {{-- <th>Password</th> --}}
                        <th>Phone</th>
                       
                        <th>Active</th>

                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($newusers as $newuser)
                    <tr>
                        <td>#{{ $newuser->id }}</td>
                        <td>{{ $newuser->name }}</td>
                        <td>{{ $newuser->email }}</td>
                        {{-- <td>{{ $newuser->password }}</td> --}}
                        <td>{{ $newuser->phone }}</td>
                     
                        <td>{{ $newuser->is_active ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('newusers.edit', $newuser->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>

                            <a href="#" class="btn btn-sm btn-danger" onclick="showConfirmation('{{ route('newusers.destroy' , $newuser->id) }}')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>

                        <!-- JavaScript -->


                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="my-2">
                <div class="my-2">
                    {{ $newusers->appends(request()->query())->links('pagination::bootstrap-5') }}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    function showConfirmation(deleteUrl) {
        // Create a Bootstrap modal for confirmation
        var modal = `
            <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this item?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                            <a href="${deleteUrl}" class="btn btn-danger">Yes</a>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Append the modal to the body
        document.body.insertAdjacentHTML('beforeend', modal);

        // Show the modal
        var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        confirmationModal.show();

        // Dismiss the modal when "No" button is clicked
        var noButton = document.querySelector('#confirmationModal .modal-footer button[data-bs-dismiss="modal"]');
        noButton.addEventListener('click', function() {
            confirmationModal.hide();
        });

        // Dismiss the modal when close button is clicked
        var closeButton = document.querySelector('#confirmationModal .modal-header .btn-close');
        closeButton.addEventListener('click', function() {
            confirmationModal.hide();
        });
    }
</script>