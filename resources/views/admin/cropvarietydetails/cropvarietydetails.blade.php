@extends('admin.common.layout')

@section('title', 'All Crop Variety Management')

@section('main')
    <div id="successAlert" class="alert alert-success alert-dismissible fade d-none" role="alert">
        <span class="alert-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">All Crop Variety Management</h5>
            <div>
                <a href="{{ route('admin.cropvarietydetails.createvariety') }}" class="btn btn-outline-success">
                    <i class="tf-icons bx bx-plus-medical"></i>Add Variety
                </a>
            </div>
        </div>

        <div class="px-4 py-2">
            <div class="mb-3">
                <label for="crop_type_cd" class="form-label">Select Crop Type</label>
                <select class="form-select" id="crop_type_cd">
                    <option value="">Select Crop Type</option>
                    @foreach ($cropTypes as $code => $description)
                        <option value="{{ $code }}">{{ $description }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="crop_name_cd" class="form-label">Select Crop Name</label>
                <select class="form-select" id="crop_name_cd">
                    <option value="">Select Crop Name</option>
                </select>
            </div>
        </div>

        <div class="table-responsive text-nowrap px-4">
            <table class="table" id="tblUser" style="display:none;">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Variety Name</th>
                        <th>Variety Name Assamese</th>
                        <th>Variety Details</th>
                        <th>Variety Details Assamese</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0" id="varietyDetails">

                </tbody>
            </table>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Variety</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editForm" action="{{ route('admin.cropvarietydetails.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="col mb-3">
                                <label for="crop_type_id" class="form-label">Select Crop Type</label>
                                <select class="form-select @error('crop_type_id') is-invalid @enderror" id="crop_type_id"
                                    name="crop_type_cd">
                                    <option value="">Select Crop Type</option>
                                    @foreach ($cropTypes as $id => $desc)
                                        <option value="{{ $id }}">{{ $desc }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback crop-type-feedback" style="display: none;">
                                    Please select a Crop Type.
                                </div>
                            </div>
                            <div class="col mb-3">
                                <label for="crop_name_id" class="form-label">Select Crop Name</label>
                                <select class="form-select @error('crop_name_id') is-invalid @enderror" id="crop_name_id"
                                    name="crop_name_id">
                                    <option value="">Select Crop Name</option>
                                </select>
                                <div class="invalid-feedback crop-name-feedback" style="display: none;">
                                    Please select a Crop Name.
                                </div>
                            </div>
                            <input type="hidden" name="crop_variety_cd" id="crop_variety_cd">
                            <div class="mb-3">
                                <label for="cropVariety" class="form-label">Crop Variety</label>
                                <input type="text" class="form-control" id="cropVariety" name="crop_variety_desc"
                                    placeholder="Enter Crop Variety">
                                <div class="invalid-feedback cropVariety-feedback" style="display: none;">
                                    Please provide Crop variety
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="cropVarietyAs" class="form-label">Crop Variety Assamese</label>
                                <input type="text" class="form-control" id="cropVarietyAs" name="crop_variety_desc_as"
                                    placeholder="Enter Crop Variety (Assamese)">
                            </div>
                            <div class="mb-3">
                                <label for="cropVarietyDetails" class="form-label">Crop Variety Details</label>
                                <textarea class="form-control" rows="4" id="cropVarietyDetails" name="crop_variety_dtls"
                                    placeholder="Enter Crop Variety Details"></textarea>
                                <div class="invalid-feedback cropVarietyDetails-feedback" style="display: none;">
                                    Please provide Crop variety Details
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="cropVarietyDetailsAs" class="form-label">Crop Variety Details Assamese</label>
                                <textarea class="form-control" rows="4" id="cropVarietyDetailsAs" name="crop_variety_dtls_as"
                                    placeholder="Enter Crop Variety Details (Assamese)"></textarea>
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
                        <h5 class="modal-title" id="deleteModalLabel">Delete Variety</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="deleteForm" method="POST"
                        action="{{ route('admin.cropvarietydetails.cropvarietydetails.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p>Are you sure you want to delete this Variety?</p>
                            <input type="hidden" name="crop_variety_cd" id="deleteId">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Delete</button>
                            <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
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
            const cropTypeSelect = document.getElementById('crop_type_cd');
            const cropNameSelect = document.getElementById('crop_name_cd');
            const tblUser = document.getElementById('tblUser');
            const varietyDetails = document.getElementById('varietyDetails');
            let cropTypeCd = '';
            let cropNameCd = '';

            cropTypeSelect.addEventListener('change', function() {
                cropTypeCd = this.value;
                cropNameSelect.innerHTML = '<option value="">Select Crop Name</option>';
                tblUser.style.display = 'none';
                varietyDetails.innerHTML = ''; // Clear previous entries

                if (cropTypeCd) {
                    fetch(`/admin/crop-names?crop_type_cd=${cropTypeCd}`)
                        .then(response => response.json())
                        .then(data => {

                            if ($.fn.dataTable.isDataTable('#tblUser')) {
                                $('#tblUser').DataTable().clear()
                                    .destroy(); // Destroy the DataTable instance
                            }

                            const sortedData = Object.entries(data)
                                .sort((a, b) => {
                                    // Sort based on crop name (value)
                                    return a[1].localeCompare(b[1]);
                                });

                            sortedData.forEach(([key, value]) => {
                                const capitalizedValue = value
                                    .toUpperCase(); // Convert to uppercase
                                const option = document.createElement('option');
                                option.value = key;
                                option.textContent = capitalizedValue;
                                cropNameSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching crop names:', error));
                }
            });

            cropNameSelect.addEventListener('change', function() {
                cropNameCd = this.value;
                tblUser.style.display = 'none';
                varietyDetails.innerHTML = ''; // Clear previous entries

                if (cropNameCd) {
                    fetch(`/admin/crop-varieties?crop_name_cd=${cropNameCd}`)
                        .then(response => response.json())
                        .then(data => {
                            if ($.fn.dataTable.isDataTable('#tblUser')) {
                                $('#tblUser').DataTable().clear()
                                    .destroy(); // Destroy the DataTable instance
                            }
                            console.log('Variety details data:', data); // Debugging line
                            if (data.length) {
                                const varietyDetails = $('#varietyDetails');
                                varietyDetails.empty();
                                data.forEach((item, index) => {
                                    const row = `
                                    <tr data-variety-cd="${item.crop_variety_cd}" data-original-index="${index + 1}">
                                        <td>${index + 1}</td>
                                        <td style="overflow-wrap: break-word; white-space: normal;">${item.crop_variety_desc}</td>
                                        <td style="overflow-wrap: break-word; white-space: normal;">${item.crop_variety_desc_as}</td>
                                        <td style="overflow-wrap: break-word; white-space: normal;">${item.crop_variety_dtls}</td>
                                        <td style="overflow-wrap: break-word; white-space: normal;">${item.crop_variety_dtls_as}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal"
                                                data-bs-target="#editModal" data-crop_variety_cd="${item.crop_variety_cd}"
                                                data-crop-name="${cropNameCd}"
                                                data-crop-type-cd="${cropTypeCd}"
                                                data-crop-variety-desc="${item.crop_variety_desc}"
                                                data-crop-variety-desc-as="${item.crop_variety_desc_as}"
                                                data-crop-variety-dtls="${item.crop_variety_dtls}"
                                                data-crop-variety-dtls-as="${item.crop_variety_dtls_as}">
                                                <i class="tf-icons bx bx-edit"></i> Edit
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" data-crop_variety_cd="${item.crop_variety_cd}">
                                                <i class="tf-icons bx bx-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                `;
                                    varietyDetails.append(row);
                                });
                                tblUser.style.display = 'table';
                                $('#tblUser').DataTable();
                            } else {
                                console.log('No variety details found'); // Debugging line
                            }
                        })
                        .catch(error => console.error('Error fetching variety details:', error));
                }
            });

            $('#editModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget); // Button that triggered the modal
                const cropTypeCd = button.data('crop-type-cd'); // Extract info from data-* attributes
                const cropNameCd = button.data('crop-name'); // Get the selected crop name
                const cropVarietyDesc = button.data('crop-variety-desc'); // Get variety description
                const cropVarietyDescAs = button.data(
                    'crop-variety-desc-as'); // Get variety description Assamese
                const cropVarietyDtls = button.data('crop-variety-dtls'); // Get variety details
                const cropVarietyDtlsAs = button.data(
                    'crop-variety-dtls-as'); // Get variety details Assamese
                const crop_variety_cd = button.data('crop_variety_cd');


                const modal = $(this);
                modal.find('#crop_type_id').val(cropTypeCd);
                modal.find('#crop_variety_cd').val(crop_variety_cd);
                modal.find('#cropVariety').val(cropVarietyDesc);
                modal.find('#cropVarietyAs').val(cropVarietyDescAs);
                modal.find('#cropVarietyDetails').val(cropVarietyDtls);
                modal.find('#cropVarietyDetailsAs').val(cropVarietyDtlsAs);

                console.log('Modal opened with data:', {
                    cropVarietyDesc,
                    cropVarietyDescAs,
                    cropVarietyDtls,
                    cropVarietyDtlsAs
                });

                fetchCropNames(cropTypeCd, cropNameCd);
            });

            // Change event for crop type dropdown in modal
            $('#editModal').on('change', '#crop_type_id', function() {
                const selectedCropTypeCd = $(this).val(); // Get the selected crop type code
                fetchCropNames(selectedCropTypeCd,
                    null); // Fetch crop names, no selected crop name initially
            });

            function fetchCropNames(cropTypeCd, cropNameCd) {
                fetch(`/admin/crop-names?crop_type_cd=${cropTypeCd}`)
                    .then(response => response.json())
                    .then(data => {
                        populateCropNameSelect(data, cropNameCd); // Call function to populate select
                    })
                    .catch(error => console.error('Error fetching crop names:', error));
            }

            function populateCropNameSelect(data, cropNameCd) {
                const cropNameSelect = $('#editModal').find('#crop_name_id');
                cropNameSelect.empty();
                cropNameSelect.append('<option value="">Select Crop Name</option>');
                const sortedEntries = Object.entries(data)
                    .map(([key, value]) => [key, value.toUpperCase()]) // Convert to uppercase
                    .sort((a, b) => a[1].localeCompare(b[1])); // Sort alphabetically by value

                // Append sorted options to the select element
                sortedEntries.forEach(([key, value]) => {
                    cropNameSelect.append(new Option(value, key));
                });

                if (cropNameCd && data.hasOwnProperty(cropNameCd)) {
                    cropNameSelect.val(cropNameCd);
                } else {
                    console.warn('Crop name not found in options:', cropNameCd);
                }
            }



            $('#editForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                var isValid = true;
                var cropVariety = $('#cropVariety').val().trim();
                var cropVarietyAs = $('#cropVarietyAs').val().trim();
                var cropVarietyDetails = $('#cropVarietyDetails').val().trim();
                var cropVarietyDetailsAs = $('#cropVarietyDetailsAs').val().trim();
                var cropTypeCd = $('#crop_type_id').val().trim();
                var cropNameCd = $('#crop_name_id').val().trim();
                var form = $(this);

                // Clear any previous invalid styles
                $('#cropVariety').removeClass('is-invalid');
                $('#cropVarietyDetails').removeClass('is-invalid');
                $('#crop_type_id').removeClass('is-invalid');
                $('#crop_name_id').removeClass('is-invalid');
                $('.invalid-feedback').hide();

                // Validate inputs
                if (cropTypeCd === '') {
                    $('#crop_type_id').addClass('is-invalid');
                    $('.invalid-feedback.crop-type-feedback').show();
                    isValid = false;
                }

                if (cropNameCd === '') {
                    $('#crop_name_id').addClass('is-invalid');
                    $('.invalid-feedback.crop-name-feedback').show();
                    isValid = false;
                }

                if (cropVariety === '') {
                    $('#cropVariety').addClass('is-invalid');
                    $('.invalid-feedback.cropVariety-feedback').show();
                    isValid = false;
                }

                if (cropVarietyDetails === '') {
                    $('#cropVarietyDetails').addClass('is-invalid');
                    $('.invalid-feedback.cropVarietyDetails-feedback').show();
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

                                // Make the alert visible
                                $('#successAlert').removeClass('d-none').addClass('show');

                                // Optionally, hide the alert after 5 seconds
                                setTimeout(function() {
                                    $('#successAlert').removeClass('show').addClass(
                                        'd-none');
                                }, 5000);

                                // Find the row to update by its data attribute (e.g., crop_variety_cd)
                                const targetRow = $(
                                    `#tblUser tbody tr[data-variety-cd="${response.updatedVariety.crop_variety_cd}"]`
                                );

                                // Update the row content with new values
                                targetRow.find('td:nth-child(2)').text(response.updatedVariety
                                    .crop_variety_desc); // Column 2: Description
                                targetRow.find('td:nth-child(3)').text(response.updatedVariety
                                    .crop_variety_desc_as); // Column 3: Description AS
                                targetRow.find('td:nth-child(4)').text(response.updatedVariety
                                    .crop_variety_dtls); // Column 4: Details
                                targetRow.find('td:nth-child(5)').text(response.updatedVariety
                                    .crop_variety_dtls_as); // Column 5: Details AS

                                const editButton = targetRow.find('.edit-btn');
                                editButton.data('crop-variety-desc', response.updatedVariety
                                    .crop_variety_desc);
                                editButton.data('crop-variety-desc-as', response.updatedVariety
                                    .crop_variety_desc_as);
                                editButton.data('crop-variety-dtls', response.updatedVariety
                                    .crop_variety_dtls);
                                editButton.data('crop-variety-dtls-as', response.updatedVariety
                                    .crop_variety_dtls_as);

                                // Update other data attributes if necessary
                                editButton.data('crop-name', response.updatedVariety.crop_name);
                                editButton.data('crop-type-cd', response.updatedVariety
                                    .crop_type_cd);

                                // Optionally, update other data attributes if needed (if you're using them for further logic)
                                targetRow.data('original-index', response.updatedVariety
                                    .crop_variety_cd); // Update data attribute (if needed)

                                // Recalculate serial numbers after update
                                var table = $('#tblUser').DataTable();
                                table.draw(false); // Ensure the page stays the same
                                updateSerialNumbers(table); // Manually update serial numbers



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
                var crop_variety_cd = button.data('crop_variety_cd');

                var modal = $(this);
                modal.find('#deleteId').val(crop_variety_cd);
            });

            $('#deleteForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);

                $.ajax({
                    url: form.attr(
                        'action'), // Ensure the form action URL points to the destroy endpoint
                    method: 'DELETE',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log('Response:', response);

                        if (response.success) {

                            var rowToDelete = $('#tblUser tbody tr[data-variety-cd="' + response.crop_variety_cd + '"]');
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
