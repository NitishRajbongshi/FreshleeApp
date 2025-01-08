@extends('admin.common.layout')

@section('title', 'User Role Management')

@section('custom_header')
@endsection

@section('main')

    @if ($message = Session::get('success'))
        <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">User Role Management</h5>
        </div>

        <div class="table-responsive text-nowrap px-4">
            <table class="table" id="tblUser">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php
                    $groupedData = $data->groupBy('user_id');
                    $serialNumber = 1; // Initialize serial number counter
                    @endphp

                    @foreach ($groupedData as $userId => $items)
                        @php
                            // Collect all roles for this user_id
                            $rolesForUser = $items
                                ->pluck('role_id')
                                ->flatMap(function ($roleIds) {
                                    return explode(',', $roleIds);
                                })
                                ->unique()
                                ->map('trim');
                        @endphp
                        @foreach ($items as $index => $item)
                            <tr>
                                <td>{{ $serialNumber++ }}</td>
                                <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->name }}</td>
                                <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->email }}</td>
                                <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->address }}</td>
                                <td style="overflow-wrap: break-word; white-space: normal;">
                                    @foreach (explode(',', $item->role_id) as $roleId)
                                        {{ $roleId }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal"
                                        data-bs-target="#editModal" data-user-id="{{ $userId }}"
                                        data-name="{{ $item->name }}" data-email="{{ $item->email }}"
                                        data-address="{{ $item->address }}"
                                        data-roles="{{ $rolesForUser->implode(',') }}">
                                        <i class="tf-icons bx bx-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit User Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.usermanagement.usermanagement.updateUserRoles') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <p><strong>Name:</strong> <span id="modalName" class="fw-bold"></span></p>
                        </div>

                        <div class="mb-3">
                            <p><strong>Email:</strong> <span id="modalEmail" class="fw-bold"></span></p>
                        </div>
                        <div class="mb-3">
                            <p><strong>Address:</strong> <span id="modalAddress" class="fw-bold"></span></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Roles</label>
                            <div class="d-flex">
                                @foreach (['F', 'TR'] as $roleTitle)
                                    @if ($roles->has($roleTitle))
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="checkbox" id="role_{{ $roleTitle }}"
                                                name="roles[]" value="{{ $roleTitle }}"
                                                @if ($roleTitle == 'F') checked disabled @endif>
                                            <label class="form-check-label" for="role_{{ $roleTitle }}">{{ $roles[$roleTitle] }}</label>
                                            @if ($roleTitle == 'F')
                                                <input type="hidden" name="roles[]" value="{{ $roleTitle }}">
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- <div class="mb-3">
                            <label class="form-label">Roles</label>
                            <div class="d-flex flex-wrap">
                                @foreach ($roles as $roleTitle => $desc)
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" id="role_{{ $roleTitle }}"
                                            name="roles[]" value="{{ $roleTitle }}">
                                        <label class="form-check-label" for="role_{{ $roleTitle }}">
                                            {{ $desc }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div> --}}

                        <input type="hidden" name="user_id" id="userId">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const allElements = document.querySelectorAll('*');
                  allElements.forEach(el => {
                      el.style.fontSize = '14px';
                  });
      $('#tblUser').DataTable();
            var successAlert = document.getElementById('successAlert');

            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.opacity = '0';
                    successAlert.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(function() {
                        successAlert.remove();
                    }, 500);
                }, 5000);
            }
        });

        $(document).ready(function() {
            $('#tblUser').DataTable();

            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var name = button.data('name');
                var userId = button.data('user-id');
                var email = button.data('email');
                var address = button.data('address');
                var roles = button.data('roles').split(',').map(role => role.trim());

                var modal = $(this);
                modal.find('#modalName').text(name);
                modal.find('#userId').val(userId);
                modal.find('#modalEmail').text(email);
                modal.find('#modalAddress').text(address);

                // Ensure Role F is always checked
                modal.find('input[type="checkbox"]').prop('checked', false);
                modal.find('input[type="checkbox"][disabled]').prop('checked', true);

                roles.forEach(function(role) {
                    var checkbox = modal.find('#role_' + role);
                    if (checkbox.length) {
                        checkbox.prop('checked', true);
                    }
                });
            });
        });
    </script>
@endsection
