@extends('admin.common.layout')

@section('title', 'Crop Type Management')

@section('custom_header')
@endsection

@section('main')
    <div id="successAlert" class="alert alert-success alert-dismissible fade d-none" role="alert">
        <span class="alert-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">Crop Types Management</h5>
            <div>
                <a href="{{ route('admin.appmaster.createcroptype') }}" class="btn btn-outline-success">
                    <i class="tf-icons bx bx-plus-medical"></i>Add Crop Type
                </a>
            </div>
        </div>

        <div class="table-responsive text-nowrap px-4">
            <table class="table" id="tblCropType">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Crop Type</th>
                        <th>Crop Type Assamese</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($data as $index => $item)
                        <tr data-crop-type-cd= {{$item->crop_type_cd}} data-original-index="{{$index + 1}}">
                            <td>{{ $index + 1 }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->crop_type_desc }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->crop_type_desc_as }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#editModal" data-id="{{ $item->crop_type_cd }}"
                                    data-crop-type="{{ $item->crop_type_desc }}"
                                    data-crop-type-as="{{ $item->crop_type_desc_as }}">
                                    <i class="tf-icons bx bx-edit"></i> Edit
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-crop_type_cd="{{ $item->crop_type_cd }}">
                                    <i class="tf-icons bx bx-trash"></i>
                                </a>
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


    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Crop Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST" action="{{ route('admin.appmaster.croptype.update') }}"
                    autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="crop_type_cd" id="cropTypeCd">
                        <div class="mb-3">
                            <label for="cropType" class="form-label">Crop Type</label>
                            <input type="text" class="form-control" id="cropType" name="crop_type_desc"
                                placeholder="Enter Crop Type">
                            <div class="invalid-feedback crop-type-feedback" style="display: none;">
                                Please provide a Crop Type.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="cropTypeAs" class="form-label">Crop Type Assamese</label>
                            <input type="text" class="form-control" id="cropTypeAs" name="crop_type_desc_as"
                                placeholder="Enter Crop Type (Assamese)">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Crop Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteForm" method="POST" action="{{ route('admin.appmaster.croptype.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Are you sure you want to delete this crop type?</p>
                        <input type="hidden" name="crop_type_cd" id="deleteId">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        $(document).ready(function() {
            const allElements = document.querySelectorAll('*');
                  allElements.forEach(el => {
                      el.style.fontSize = '14px';
                  });
                  
            $('#tblCropType').DataTable();

            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var cropTypeCd = button.data('id');
                var cropType = button.data('crop-type');
                var cropTypeAs = button.data('crop-type-as');

                var modal = $(this);
                modal.find('#cropTypeCd').val(cropTypeCd);
                modal.find('#cropType').val(cropType);
                modal.find('#cropTypeAs').val(cropTypeAs);
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                var isValid = true;
                var cropType = $('#cropType').val().trim();
                var cropTypeCd = $('#cropTypeCd').val().trim();
                var form = $(this);

                $('#cropType').removeClass('is-invalid');
                $('.invalid-feedback').hide();

                if (cropType === '') {
                    $('#cropType').addClass('is-invalid');
                    $('.invalid-feedback').filter('.crop-type-feedback').show();
                    isValid = false;
                }

                if (isValid) {
                    $.ajax({
                        url: form.attr('action'),
                        method: 'PUT',
                        data: form.serialize(),
                        dataType: 'json',
                        success: function(response) {
                            console.log(response.message);

                            if (response.success) {
                                $('#editModal').modal('hide');
                                $('#successAlert .alert-message').text(response.message);
                                $('#successAlert').removeClass('d-none').addClass('show');

                                setTimeout(function() {
                                    $('#successAlert').removeClass('show').addClass('d-none');
                                }, 5000);

                                const targetRow = $(
                                    `#tblCropType tbody tr[data-crop-type-cd="${response.updatedCropType.crop_type_cd}"]`
                                );

                                targetRow.find('td:nth-child(2)').text(response.updatedCropType.crop_type_desc);
                                targetRow.find('td:nth-child(3)').text(response.updatedCropType.crop_type_desc_as);

                                const editButton = targetRow.find('.edit-btn');
                                editButton.data('crop-type', response.updatedCropType.crop_type_desc);
                                editButton.data('crop-type-as', response.updatedCropType.crop_type_desc_as);
                                editButton.data('id', response.updatedCropType.crop_type_cd);

                                targetRow.data('original-index', response.updatedCropType.crop_type_cd);
                                var table = $('#tblCropType').DataTable();
                                table.draw(false);
                                updateSerialNumbers(table);

                            } else {
                                alert('Failed to update variety.');
                            }
                        },
                        error: function(xhr) {
                            console.error('Error updating variety:', xhr.responseText);
                            alert('There was an error updating the variety.');
                        }
                    });
                }
            });

            function updateSerialNumbers(table) {
                table.rows().every(function(index) {
                    var row = this.node();
                    var serialNumber = index + 1;
                    $(row).find('td:first').text(serialNumber);
                });
            }

            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var crop_type_cd = button.data('crop_type_cd');

                var modal = $(this);
                modal.find('#deleteId').val(crop_type_cd);
            });

            $('#deleteForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);

                $.ajax({
                    url: form.attr('action'),
                    method: 'DELETE',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {


                        if (response.success) {
                            var rowToDelete = $('#tblCropType tbody tr[data-crop-type-cd="' + response.crop_type_cd + '"]');
                            var table = $('#tblCropType').DataTable();

                            table.row(rowToDelete).remove().draw(false);
                            table.draw(false);

                            updateSerialNumbers(table);

                            $('#successAlert .alert-message').text(response.message);
                            $('#successAlert').removeClass('d-none').addClass('show');

                            setTimeout(function() {
                                $('#successAlert').removeClass('show').addClass(
                                    'd-none');
                            }, 5000);

                            $('#deleteModal').modal('hide');
                        } else {
                            console.error('Failed to delete variety:', response.message);
                            alert('Failed to delete the variety.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error deleting variety:', xhr.responseText);
                        alert('There was an error deleting the variety.');
                    }
                });
            });


            function updateSerialNumbers(table) {
                table.rows().every(function(index) {
                    var row = this.node();
                    var serialNumber = index + 1;
                    $(row).find('td:first').text(serialNumber);
                });
            }
        });
    </script>
@endsection
