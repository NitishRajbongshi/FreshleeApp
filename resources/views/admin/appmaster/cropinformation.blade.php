@extends('admin.common.layout')

@section('title', 'Crop Name Management')

@section('custom_header')
@endsection

@section('main')
    <div id="successAlert" class="alert alert-success alert-dismissible fade d-none" role="alert">
        <span class="alert-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">Crop Information Management</h5>
            <div>
                <a href="{{ route('admin.appmaster.createcrop') }}" class="btn btn-outline-success">
                    <i class="tf-icons bx bx-plus-medical"></i>Add Crop
                </a>
            </div>
        </div>

        <div class="table-responsive text-nowrap px-4">
            <table class="table" id="tblUser">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Crop Name</th>
                        <th>Crop Name Assamese</th>
                        <th>Crop Registry No</th>
                        <th>Scientific Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($data as $index => $item)
                        <tr data-crop-name-cd={{ $item->crop_name_cd }} data-original-index="{{ $index + 1 }}">
                            <td>{{ $index + 1 }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->crop_name_desc }}</>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->crop_name_desc_as }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->crop_registry_no }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->scientific_name }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#editModal" data-id="{{ $item->crop_name_cd }}"
                                    data-crop-name="{{ $item->crop_name_desc }}"
                                    data-crop-name-as="{{ $item->crop_name_desc_as }}"
                                    data-registry-no="{{ $item->crop_registry_no }}"
                                    data-scientific-name="{{ $item->scientific_name }}">
                                    <i class="tf-icons bx bx-edit"></i> Edit
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-crop_name_cd="{{ $item->crop_name_cd }}">
                                    <i class="tf-icons bx bx-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No data found.</td>
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
                <form id="editForm" method="POST" action="{{ route('admin.appmaster.crops.update') }}"
                    autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="crop_name_cd" id="cropNameCd">
                        <div class="mb-3">
                            <label for="cropName" class="form-label">Crop Name</label>
                            <input type="text" class="form-control" id="cropName" name="crop_name_desc"
                                placeholder=" Enter Crop Name">
                            <div class="invalid-feedback crop-name-feedback" style="display: none;">
                                Please provide a Crop Name.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="cropNameAs" class="form-label">Crop Name Assamese</label>
                            <input type="text" class="form-control" id="cropNameAs" name="crop_name_desc_as"
                                placeholder=" Enter Crop Name (Assamese)">
                        </div>
                        <div class="mb-3">
                            <label for="registryNo" class="form-label">Crop Registry No</label>
                            <input type="text" class="form-control" id="registryNo" name="crop_registry_no"
                                placeholder=" Enter Crop Registry No">
                            <div class="invalid-feedback registry-no-feedback" style="display: none;">
                                The Crop Registry No is already in use.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="scientificName" class="form-label">Scientific Name</label>
                            <input type="text" class="form-control" id="scientificName" name="scientific_name"
                                placeholder=" Enter Scientific Name">
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
                    <h5 class="modal-title" id="deleteModalLabel">Delete Crop Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteForm" method="POST" action="{{ route('admin.appmaster.crops.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Are you sure you want to delete this crop information?</p>
                        <input type="hidden" name="crop_name_cd" id="deleteId">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
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
                  
                $('#tblUser').DataTable();

                $('#editModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var cropNameCd = button.data('id');
                    var cropName = button.data('crop-name');
                    var cropNameAs = button.data('crop-name-as');
                    var registryNo = button.data('registry-no');
                    var scientificName = button.data('scientific-name');

                    var modal = $(this);
                    modal.find('#cropNameCd').val(cropNameCd);
                    modal.find('#cropName').val(cropName);
                    modal.find('#cropNameAs').val(cropNameAs);
                    modal.find('#registryNo').val(registryNo);
                    modal.find('#scientificName').val(scientificName);
                });

                $('#editForm').on('submit', function(e) {
                    e.preventDefault(); // Prevent form submission

                    var isValid = true;
                    var cropName = $('#cropName').val().trim();
                    var registryNo = $('#registryNo').val().trim();
                    var cropNameCd = $('#cropNameCd').val().trim();
                    var form = $(this);

                    // Clear previous error states
                    $('#cropName').removeClass('is-invalid');
                    $('#registryNo').removeClass('is-invalid');
                    $('.invalid-feedback').hide();

                    // Check Crop Name field
                    if (cropName === '') {
                        $('#cropName').addClass('is-invalid');
                        $('.invalid-feedback').filter('.crop-name-feedback').show();
                        isValid = false;
                    }


                    if (registryNo !== '') {
                        $.ajax({
                            url: '{{ route('admin.appmaster.crops.checkRegistryNo') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                registry_no: registryNo,
                                crop_name_cd: cropNameCd
                            },
                            success: function(response) {
                                if (response.exists) {
                                    $('#registryNo').addClass('is-invalid');
                                    $('.invalid-feedback').filter('.registry-no-feedback').text(
                                        'The Crop Registry No is already in use.').show();
                                    isValid = false;
                                } else {
                                    $('#registryNo').removeClass('is-invalid');
                                    $('.invalid-feedback').filter('.registry-no-feedback').hide();
                                }

                                // if (isValid) {
                                //     form.off('submit').submit();
                                // }



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
                                                $('#successAlert .alert-message').text(
                                                    response.message);
                                                $('#successAlert').removeClass('d-none')
                                                    .addClass('show');

                                                setTimeout(function() {
                                                    $('#successAlert')
                                                        .removeClass('show')
                                                        .addClass('d-none');
                                                }, 5000);

                                                const targetRow = $(
                                                    `#tblUser tbody tr[data-crop-name-cd="${response.updatedCropName.crop_name_cd}"]`
                                                );

                                                targetRow.find('td:nth-child(2)').text(response.updatedCropName.crop_name_desc);
                                                targetRow.find('td:nth-child(3)').text(response.updatedCropName.crop_name_desc_as);
                                                targetRow.find('td:nth-child(4)').text(response.updatedCropName.crop_registry_no);
                                                targetRow.find('td:nth-child(5)').text(response.updatedCropName.scientific_name);

                                                const editButton = targetRow.find('.edit-btn');
                                                editButton.data('crop-name', response.updatedCropName.crop_name_desc);
                                                editButton.data('crop-name-as', response.updatedCropName.crop_name_desc_as);
                                                editButton.data('id', response.updatedCropName.crop_name_cd);
                                                editButton.data('registry-no', response.updatedCropName.crop_registry_no);
                                                editButton.data('scientific-name', response.updatedCropName.scientific_name);


                                                targetRow.data('original-index',response.updatedCropName.crop_name_cd);
                                                var table = $('#tblUser').DataTable();
                                                table.draw(false);
                                                updateSerialNumbers(table);
                                            } else {
                                                alert('Failed to update variety.');
                                            }
                                        },
                                        error: function(xhr) {
                                            console.error('Error updating variety:', xhr
                                                .responseText);
                                            alert(
                                                'There was an error updating the variety.');
                                        }
                                    });
                                }

                            },
                            error: function(xhr) {
                                console.error('AJAX Error:', xhr); // Debugging output
                            }
                        });
                    } else {
                        // If the registry number is empty, submit the form directly
                        if (isValid) {
                            form.off('submit').submit(); // Allow form submission
                        }
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
                    var crop_name_cd = button.data('crop_name_cd');

                    var modal = $(this);
                    modal.find('#deleteId').val(crop_name_cd);
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
                                var rowToDelete = $('#tblUser tbody tr[data-crop-name-cd="' +
                                    response.crop_name_cd + '"]');
                                var table = $('#tblUser').DataTable();

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
