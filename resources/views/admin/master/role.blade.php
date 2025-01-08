@extends('admin.common.layout')

@section('title', 'Role Master')

@section('custom_header')
@endsection

@section('main')

<div class="card">
    <div class="d-flex align-items-center">
        <h5 class="card-header">Enlisted Roles</h5>
        <div>
            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#createRoleModal">
              <i class="tf-icons bx bx-plus-medical"></i>
              Add New Role
              </button>
        </div>
    </div>
    <div class="table-responsive text-nowrap px-4">
      <table class="table" id="tblRole">
        <thead>
          <tr>
            <th>Sl.No.</th>
            <th>Role</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">

            @forelse ($roles as $index => $item)
                <tr>
                    <td>
                    <strong>{{$index + 1}}</strong>
                    </td>
                    <td>{{$item->role_title}}</td>
                    <td>{{$item->role_desc}}</td>
                    <td>
                    <button class="btn btn-outline-primary btn-sm OpenEditModalBtn"
                    data-id="{{Crypt::encrypt($item->id)}}"
                    data-role_title="{{$item->role_title}}"
                    data-role_desc ="{{$item->role_desc}}">
                    <i class='bx bx-edit'></i>Edit</button>

                    <button class="btn btn-sm btn-outline-danger deleteBtn"
                    data-id="{{Crypt::encrypt($item->id)}}">
                    <i class='bx bx-trash'></i>Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No data found</td>
                </tr>
            @endforelse

        </tbody>
      </table>
    </div>
  </div>

  {{-- Create Role Modal --}}
  <div class="modal fade" id="createRoleModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createRoleModalTitle">Add New Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="#" id="createRoleForm">
            @csrf
            <div class="modal-body">

                {{-- Role Title --}}
                <div class="row">
                  <div class="col mb-3">
                    <label for="role_title" class="form-label">Role Title</label>
                    <input type="text" id="role_title" name="role_title" class="form-control" placeholder="Enter Title" required>
                  </div>
                </div>

                {{-- Role Desc --}}
                <div class="row">
                    <div class="col mb-3">
                      <label for="role_title" class="form-label">Role Description</label>
                      <input type="text" id="role_desc" name="role_desc" class="form-control" placeholder="Enter Description" required>
                    </div>
                </div>

              </div>

              {{-- Modal buttons --}}
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                  Close
                </button>
                <button type="submit" class="btn btn-primary" id="createRoleBtn">Add</button>
              </div>

        </form>
      </div>
    </div>
  </div>

  {{-- Edit Role Modal --}}
  <div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editRoleModalTitle">Edit Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="#" id="editRoleForm" method="POST">
            @csrf
            <div class="modal-body">
                <div class="row">
                  <div class="col mb-3">
                    <input type="hidden" name="role_id" id="role_id" value="">
                    <label for="edit_role_title" class="form-label">Role title</label>
                    <input type="text" id="edit_role_title" name="edit_role_title"
                    class="form-control @error('edit_role_title') is-invalid @enderror"
                    placeholder="Enter Role Title" required>
                  </div>
                </div>

                <div class="row">
                  <div class="col mb-3">
                    <label for="edit_role_desc" class="form-label">Role Delcription</label>
                    <input type="text" id="edit_role_desc" name="edit_role_desc" class="form-control" placeholder="Enter Role Description">
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                  Close
                </button>
                <button type="submit" class="btn btn-primary" id="editRoleBtn">Update</button>
              </div>
        </form>
      </div>
    </div>
  </div>


@endsection


@section('custom_js')

<script>
    $('#createRoleForm').on('submit', function(e) {
        const btn = $('#createRoleBtn');
        btn.text('Please wait...');
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.role') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.status == 0) {
                    alert(data.message);
                    btn.text('Create');
                } else if (data.status == 1) {
                    alert(data.message);
                    btn.text('Create');
                    $('#createRoleModal').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                alert('Something went wrong!!');
                btn.text('Create');
            }
        });
    });
</script>

{{-- Open edit modal --}}
<script>
    $('.OpenEditModalBtn').on('click', function(){
        const id = $(this).data('id');
        $('#role_id').val(id);
        $('#edit_role_title').val( $(this).data('role_title'));
        $('#edit_role_desc').val( $(this).data('role_desc'));

        $('#editRoleModal').on('shown.bs.modal', function (e) {
          $('#edit_role_title').focus();
        }).modal('show');

    })
</script>

{{-- Edit AJAX --}}
<script>
  $('#editRoleForm').on('submit', function(e) {
      const btn = $('#editRoleBtn');
      btn.text('Please wait...');
      e.preventDefault();

      const formData = new FormData(this);

      $.ajax({
          url: "{{ route('admin.role.edit') }}",
          type: "POST",
          data: formData,
          processData: false,
          contentType: false,
          success: function(data) {
              if (data.status == 0) {
                  alert(data.message);
                  btn.text('Update');
              } else if (data.status == 1) {
                  alert(data.message);
                  btn.text('Update');
                  $('#editRoleModal').modal('hide');
                  location.reload();
              }
          },
          error: function(xhr, status, error) {
              alert('Something went wrong');
              btn.text('Update');
          }
      });
  });
</script>

<script>
  $(document).ready( function () {
    const allElements = document.querySelectorAll('*');
                  allElements.forEach(el => {
                      el.style.fontSize = '14px';
                  });
    $('#tblRole').DataTable();
  } );
</script>


@endsection
