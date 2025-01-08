@extends('admin.common.layout')

@section('title', 'Office Master')

@section('custom_header')
@endsection

@section('main')

<div class="card">
    <div class="d-flex align-items-center">
        <h5 class="card-header">Available Offices</h5>
        <div>
            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#createOfficeModal">
              <i class="tf-icons bx bx-plus-medical"></i>
              Add New Office
              </button>
        </div>
    </div>
    <div class="table-responsive text-nowrap px-4">
      <table class="table" id="tblOffice">
        <thead>
          <tr>
            <th>No.</th>
            <th>Office</th>
            <th>District</th>
            <th>State</th>
            <th>Contact</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse ($offices as $index => $item)
            <tr>
                <td>
                  <strong>{{$index + 1}}</strong>
                </td>
                <td>{{$item->name}}, <br>{{$item->address}}</td>
                <td>{{$item->district}}</td>
                <td>{{$item->state}}</td>
                <td>{{$item->contact}}</td>
                <td>
                  <button class="btn btn-sm btn-outline-primary OpenEditModalBtn"
                  data-id="{{Crypt::encrypt($item->id)}}"
                  data-name = "{{$item->name}}"
                  data-district = "{{$item->district}}"
                  data-state = "{{$item->state}}"
                  data-contact = "{{$item->contact}}"
                  data-address = "{{$item->address}}" >

                  <i class='bx bx-edit'></i>Edit</button>

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

  {{-- Create Office Modal --}}
  <div class="modal fade" id="createOfficeModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createOfficeTitle">Create Office</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="#" id="createOfficeForm">
            @csrf
            <div class="modal-body">

                {{-- Select State --}}
                <div class="row">
                    <div class="col mb-3">
                    <label for="state" class="form-label">Select State</label>

                    <select class="form-select"
                    id="state" name="state" aria-label="Select Office State">
                        <option selected disabled value="">Select Office State</option>
                        @forelse ($states as $item)
                        <option value="{{$item->state_name}}">{{$item->state_name}}</option>
                        @empty
                            <option disabled value="">No data found</option>
                        @endforelse
                    </select>

                    </div>
                </div>

                {{-- Select district --}}
                {{-- Note: State wise district loading is not done. --}}
                <div class="row">
                    <div class="col mb-3">
                    <label for="district" class="form-label">Select District</label>

                    <select class="form-select"
                    id="district" name="district" aria-label="Select Office district">
                        <option selected disabled value="">Select Office District</option>
                        @forelse ($districts as $item)
                        <option value="{{$item->district_name}}">{{$item->district_name}}</option>
                        @empty
                            <option disabled value="">No data found</option>
                        @endforelse
                    </select>

                    </div>
                </div>

                {{-- Office Name --}}
                <div class="row">
                  <div class="col mb-3">
                    <label for="name" class="form-label">Office Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter Office Name" required>
                  </div>
                </div>

                {{-- Office Name --}}
                <div class="row">
                    <div class="col mb-3">
                      <label for="address" class="form-label">Office Address</label>
                      <input type="text" id="address" name="address" class="form-control" placeholder="Enter Office Address" required>
                    </div>
                </div>

                {{-- Office Contact --}}
                <div class="row">
                    <div class="col mb-3">
                      <label for="contact" class="form-label">Office Contact</label>
                      <input type="text" id="contact" name="contact" class="form-control" placeholder="Enter Contact Number" required>
                    </div>
                </div>

              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                  Close
                </button>
                <button type="submit" class="btn btn-primary" id="createOfficeBtn">Create</button>
              </div>
        </form>
      </div>
    </div>
  </div>


  {{-- Edit Office Modal --}}
  <div class="modal fade" id="editOfficeModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editOfficeTitle">Edit Office Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="#" id="editOfficeForm">
            @csrf
            <div class="modal-body">

                {{-- Office ID --}}
                <input type="hidden" id="editId" name="editId" />

                {{-- Select State --}}
                <div class="row">
                    <div class="col mb-3">
                    <label for="editState" class="form-label">Select editS</label>

                    <select class="form-select"
                    id="editState" name="editState" aria-label="Select Office State">
                        <option selected disabled value="">Select Office State</option>
                        @forelse ($states as $item)
                        <option value="{{$item->state_name}}">{{$item->state_name}}</option>
                        @empty
                            <option disabled value="">No data found</option>
                        @endforelse
                    </select>

                    </div>
                </div>

                {{-- Select district --}}
                {{-- Note: State wise district loading is not done. --}}
                <div class="row">
                    <div class="col mb-3">
                    <label for="editDistrict" class="form-label">Select District</label>

                    <select class="form-select"
                    id="editDistrict" name="editDistrict" aria-label="Select Office District">
                        <option selected disabled value="">Select Office District</option>
                        @forelse ($districts as $item)
                        <option value="{{$item->district_name}}">{{$item->district_name}}</option>
                        @empty
                            <option disabled value="">No data found</option>
                        @endforelse
                    </select>

                    </div>
                </div>

                {{-- Office Name --}}
                <div class="row">
                  <div class="col mb-3">
                    <label for="editName" class="form-label">Office Name</label>
                    <input type="text" id="editName" name="editName" class="form-control" placeholder="Enter Office Name" required>
                  </div>
                </div>

                {{-- Office Name --}}
                <div class="row">
                    <div class="col mb-3">
                      <label for="editAddress" class="form-label">Office Address</label>
                      <input type="text" id="editAddress" name="editAddress" class="form-control" placeholder="Enter Office Address" required>
                    </div>
                </div>

                {{-- Office Contact --}}
                <div class="row">
                    <div class="col mb-3">
                      <label for="editContact" class="form-label">Office Contact</label>
                      <input type="text" id="editContact" name="editContact" class="form-control" placeholder="Enter Contact Number" required>
                    </div>
                </div>

              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                  Close
                </button>
                <button type="submit" class="btn btn-primary" id="saveOfficeBtn">Save</button>
              </div>
        </form>
      </div>
    </div>
  </div>

@endsection

@section('custom_js')
<script>
    $(document).ready( function () {
        const allElements = document.querySelectorAll('*');
                  allElements.forEach(el => {
                      el.style.fontSize = '14px';
                  });
      // Add New Office AJAX
      $('#createOfficeForm').on('submit', function(e) {
        const btn = $('#createOfficeBtn');
        btn.text('Please wait...');
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.office') }}",
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
                    $('#createOfficeModal').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                alert('Something went wrong');
                btn.text('Create');
            }
        });
    });

    // Edit Office Details Modal
    $("#tblOffice").on("click", ".OpenEditModalBtn", function(){
      const id = $(this).data('id');

      $('#editId').val( id );
      $('#editState').val($(this).data('state'));
      $('#editDistrict').val($(this).data('district'));
      $('#editName').val( $(this).data('name') );
      $('#editAddress').val( $(this).data('address') );
      $('#editContact').val( $(this).data('contact') );

      $('#editOfficeModal').on('shown.bs.modal', function (e) {
              $('#editState').focus();
            }).modal('show');
    });

    // edit Save Ajax
    $('#editOfficeForm').on('submit', function(e) {
      const btn = $('#saveOfficeBtn');
      btn.text('Please wait...');
      e.preventDefault();

      const formData = new FormData(this);
      $.ajax({
        url: "{{ route('admin.office.edit') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            if (data.status == 0) {
                alert(data.message);
                btn.text('Add');
            } else if (data.status == 1) {
                alert(data.message);
                btn.text('Add');
                $('#editOfficeModal').modal('hide');
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            alert('Something went wrong');
            btn.text('Add');
        }
      });
    });

      $('#tblOffice').DataTable();


    } );
</script>
@endsection
