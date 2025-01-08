@extends('admin.common.layout')

@section('title', 'Department Master')

@section('custom_header')
@endsection

@section('main')

<div class="card">
    <div class="d-flex align-items-center">
        <h5 class="card-header">Available Departments</h5>
        <div>
            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
              <i class="tf-icons bx bx-plus-medical"></i>
                Add New Department
            </button>
        </div>
    </div>
    <div class="table-responsive text-nowrap px-4">
      <table class="table" id="tblDepartment">
        <thead>
          <tr>
            <th>Sl.No.</th>
            <th>Department</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">

            @forelse ($departments as $index => $item)
            <tr>
                <td>
                  <strong>{{$index + 1}}</strong>
                </td>
                <td>{{$item->department_name}}</td>
                <td>
                  <button class="btn btn-sm btn-outline-primary OpenEditModalBtn"
                  data-id="{{Crypt::encrypt($item->id)}}"
                  data-department_name = "{{$item->department_name}}">
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


  {{-- Create Department Modal --}}
  <div class="modal fade" id="createDepartmentModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createDepartmentModalTitle">Add New Department</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="#" id="createDepartmentForm">
            @csrf
            <div class="modal-body">
                <div class="row">
                  <div class="col mb-3">
                    <label for="department_title" class="form-label">Department Name</label>
                    <input type="text" id="department_title" name="department_title" class="form-control" placeholder="Enter Name" required>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                  Close
                </button>
                <button type="submit" class="btn btn-primary" id="createDepartmentBtn">Add</button>
              </div>
        </form>
      </div>
    </div>
  </div>

    {{-- Edit Department Modal --}}
    <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editDepartmentModalTitle">Edit Department</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" id="editDepartmentForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                      <div class="col mb-3">
                        <input type="hidden" name="department_id" id="department_id" value="">
                        <label for="edit_department_title" class="form-label">Department title</label>
                        <input type="text" id="edit_department_title" name="edit_department_title" class="form-control" placeholder="Enter Name" required>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                      Close
                    </button>
                    <button type="submit" class="btn btn-primary" id="editDepartmentBtn">Update</button>
                  </div>
            </form>
          </div>
        </div>
      </div>
@endsection

@section('custom_js')

{{-- Create AJAX --}}
<script>
    $('#createDepartmentForm').on('submit', function(e) {
        const btn = $('#createDepartmentBtn');
        btn.text('Please wait...');
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.department') }}",
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
                    $('#createDepartmentModal').modal('hide');
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

{{-- Delete AJAX --}}
<script>
    $('.deleteBtn').on('click', function(){
        if (confirm("Aru you sure you want to delete?") == true) {
            // If confirmed
            const _id = $(this).data('id');
            const btn = $(this);
            btn.text('Please wait');
            btn.attr('disabled', true);
            $.ajax({
                url: "{{ route('admin.department.delete') }}",
                type: "POST",
                data: {
                    _id: _id
                },
                success: function(data) {
                    if (data.status == 0) {
                        alert(data.message);
                        btn.text('Delete');
                        btn.attr('disabled', false);
                    } else if (data.status == 1) {
                        alert(data.message);
                        btn.text('Delete');
                        btn.attr('disabled', true);
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    alert('Something went wrong');
                    btn.text('Delete');
                    btn.attr('disabled', false);
                }
            });
        } else {
            // If declined
            return 0;
        }
    });
</script>

{{-- Open edit modal --}}
<script>
    $('.OpenEditModalBtn').on('click', function(){
        const id = $(this).data('id');
        $('#department_id').val(id);
        $('#edit_department_title').val( $(this).data('department_name'));

        // $('#editDepartmentModal').modal('show');
        $('#editDepartmentModal').on('shown.bs.modal', function (e) {
          $('#edit_department_title').focus();
        }).modal('show');
    });
</script>


{{-- Edit AJAX --}}
<script>
    $('#editDepartmentForm').on('submit', function(e) {
        const btn = $('#editDepartmentBtn');
        btn.text('Please wait...');
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.department.edit') }}",
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
                    $('#editDepartmentModal').modal('hide');
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
    $('#tblDepartment').DataTable();
  } );
</script>
@endsection
