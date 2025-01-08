@extends('admin.common.layout')

@section('title', 'Designation Master')

@section('custom_header')
@endsection

@section('main')

<div class="card">
    <div class="d-flex align-items-center">
        <h5 class="card-header">Available Designations</h5>
        <div>
            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#createDesignationModal">
              <i class="tf-icons bx bx-plus-medical"></i>
              Create Designation
              </button>
        </div>
    </div>
    <div class="table-responsive text-nowrap px-4">
      <table class="table" id="tblDesignation">
        <thead>
          <tr>
            <th>No.</th>
            <th>Designation</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse ($designations as $index => $item)
            <tr>
                <td>
                  <strong>{{$index + 1}}</strong>
                </td>
                <td>{{$item->designation_name}}</td>
                <td>
                  <button class="btn btn-sm btn-outline-primary OpenEditModalBtn"
                  data-id="{{Crypt::encrypt($item->id)}}"
                  data-designation_name = "{{$item->designation_name}}">
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


  {{-- Create Designation Modal --}}
  <div class="modal fade" id="createDesignationModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createDesignationModalTitle">Create designation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="#" id="createDesignationForm">
            @csrf
            <div class="modal-body">
                <div class="row">
                  <div class="col mb-3">
                    <label for="designation_title" class="form-label">Designation title</label>
                    <input type="text" id="designation_title" name="designation_title" class="form-control" placeholder="Enter Name" required>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                  Close
                </button>
                <button type="submit" class="btn btn-primary" id="createDesignationBtn">Create</button>
              </div>
        </form>
      </div>
    </div>
  </div>

    {{-- Edit Designation Modal --}}
    <div class="modal fade" id="editDesignationModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editDesignationModalTitle">Edit designation</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" id="editDesignationForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                      <div class="col mb-3">
                        <input type="hidden" name="designation_id" id="designation_id" value="">
                        <label for="edit_designation_title" class="form-label">Designation title</label>
                        <input type="text" id="edit_designation_title" name="edit_designation_title" class="form-control" placeholder="Enter Name" required>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                      Close
                    </button>
                    <button type="submit" class="btn btn-primary" id="editDesignationBtn">Update</button>
                  </div>
            </form>
          </div>
        </div>
      </div>
@endsection

@section('custom_js')

{{-- Create AJAX --}}
<script>
    $('#createDesignationForm').on('submit', function(e) {
        const btn = $('#createDesignationBtn');
        btn.text('Please wait...');
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.designation') }}",
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
                    $('#createDesignationModal').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                alert('Something went wrong');
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
                url: "{{ route('admin.designation.delete') }}",
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
                    btn.attr('disabled', true);
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
        $('#designation_id').val(id);
        //$('#editDesignationModal').modal('show');

        $('#edit_designation_title').val( $(this).data('designation_name'));

        // $('#editDepartmentModal').modal('show');
        $('#editDesignationModal').on('shown.bs.modal', function (e) {
          $('#edit_designation_title').focus();
        }).modal('show');
    })
</script>


{{-- Edit AJAX --}}
<script>
    $('#editDesignationForm').on('submit', function(e) {
        const btn = $('#editDesignationBtn');
        btn.text('Please wait...');
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.designation.edit') }}",
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
                    $('#editDesignationModal').modal('hide');
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
    $('#tblDesignation').DataTable();
  } );
</script>
@endsection
