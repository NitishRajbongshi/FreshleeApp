@extends('admin.common.layout')

@section('title', 'All Crop Variety Management')

@section('main')
    <div id="successAlert" class="alert alert-success alert-dismissible fade d-none" role="alert">
        <span class="alert-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">All Crop Symptom Management</h5>
            <div>
                <a href="{{ route('admin.cropsymptomdetails.createsymptom') }}" class="btn btn-outline-success">
                    <i class="tf-icons bx bx-plus-medical"></i>Add Symptom
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

            <div class="mb-3">
                <label for="disease_cd" class="form-label">Select Disease</label>
                <select class="form-select" id="disease_cd">
                    <option value="">Select Disease</option>
                </select>
            </div>

            <div class="table-container" style="display: flex; gap: 20px; justify-content: space-between;">
                <div class="table-responsive text-nowrap px-4" style="flex: 1;">
                    <table class="table" id="tblUser1" style="display:none;">
                        <thead>
                            <tr>
                                <th>Sl. No.</th>
                                <th>Symptom</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0" id="diseaseDetailsEn">
                            <!-- English symptoms will be populated here -->
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive text-nowrap px-4" style="flex: 1;">
                    <table class="table" id="tblUser2" style="display:none;">
                        <thead>
                            <tr>
                                <th>Sl. No.</th>
                                <th>Symptom Assamese</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0" id="diseaseDetailsAs">
                            <!-- Assamese symptoms will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>




            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Symptom</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editForm" method="POST"
                            action="{{ route('admin.cropsymptomdetails.cropsymptomdetails.update') }}" autocomplete="off">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="col mb-3">
                                    <label for="crop_type_id" class="form-label">Select Crop Type</label>
                                    <select class="form-select @error('crop_type_id') is-invalid @enderror"
                                        id="crop_type_id" name="crop_type_id">
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
                                    <select class="form-select @error('crop_name_id') is-invalid @enderror"
                                        id="crop_name_id" name="crop_name_id">
                                        <option value="">Select Crop Name</option>
                                    </select>
                                    <div class="invalid-feedback crop-name-feedback" style="display: none;">
                                        Please select a Crop Name.
                                    </div>
                                </div>

                                <div class="col mb-3">
                                    <label for="disease_id" class="form-label">Select Disease</label>
                                    <select class="form-select @error('disease_id') is-invalid @enderror" id="disease_id"
                                        name="disease_id">
                                        <option value="">Select Disease</option>
                                    </select>
                                    <div class="invalid-feedback disease-id-feedback" style="display: none;">
                                        Please select a Disease.
                                    </div>
                                </div>


                                <input type="hidden" name="language_cd" id="language_cd">
                                <input type="hidden" name="id" id="id">

                                <div class="mb-3">
                                    <label for="symptom" class="form-label">Symptom</label>
                                    <textarea rows="4" class="form-control" id="symptom" name="symptom" placeholder="Enter Symptom"></textarea>
                                    <div class="invalid-feedback symptom-feedback" style="display: none;">
                                        Please provide Symptom
                                    </div>
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

            <div class="modal fade" id="imageModal" aria-labelledby="imageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="d-flex justify-content-center">
                                <img id="modalImage1" src="" alt="Image 1" class="img-fluid mx-2"
                                    style="max-width: 30%; max-height: 80vh; object-fit: contain;">
                                <img id="modalImage2" src="" alt="Image 2" class="img-fluid mx-2"
                                    style="max-width: 30%; max-height: 80vh; object-fit: contain;">
                                <img id="modalImage3" src="" alt="Image 3" class="img-fluid mx-2"
                                    style="max-width: 30%; max-height: 80vh; object-fit: contain;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Delete Suitability</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form id="deleteForm" method="POST"
                            action="{{ route('admin.cropsymptomdetails.cropsymptomdetails.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                <p>Are you sure you want to delete this suitability?</p>
                                <input type="hidden" name="id" id="deleteId">
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
            const diseaseSelect = document.getElementById('disease_cd');
            const tblUser1 = document.getElementById('tblUser1');
            const tblUser2 = document.getElementById('tblUser2');
            const diseaseDetailsEn = document.getElementById('diseaseDetailsEn');
            const diseaseDetailsAs = document.getElementById('diseaseDetailsAs');
            let cropTypeCd = '';
            let cropNameCd = '';
            let diseaseCd = '';

            cropTypeSelect.addEventListener('change', function() {
                cropTypeCd = this.value;
                cropNameSelect.innerHTML = '<option value="">Select Crop Name</option>';
                diseaseSelect.innerHTML =
                    '<option value="">Select Disease</option>'; // Clear diseases when crop type is changed
                tblUser1.style.display = 'none';
                tblUser2.style.display = 'none';
                diseaseDetailsEn.innerHTML = '';
                diseaseDetailsAs.innerHTML = '';
                if (cropTypeCd) {
                    fetch(`/admin/crop-name?crop_type_cd=${cropTypeCd}`)
                        .then(response => response.json())
                        .then(data => {

                            const sortedData = Object.entries(data).sort((a, b) => {
                                return a[1].localeCompare(b[
                                    1]); // Sort based on crop name (value)
                            });

                            sortedData.forEach(([key, value]) => {
                                const option = document.createElement('option');
                                option.value = key;
                                option.textContent = value
                                    .toUpperCase(); // Convert crop name to uppercase
                                cropNameSelect.appendChild(option);
                            });


                        })
                        .catch(error => console.error('Error fetching crop names:', error));
                }
            });

            cropNameSelect.addEventListener('change', function() {
                cropNameCd = this.value;
                diseaseSelect.innerHTML =
                    '<option value="">Select Disease</option>'; // Reset disease dropdown
                tblUser1.style.display = 'none';
                tblUser2.style.display = 'none';
                diseaseDetailsEn.innerHTML = '';
                diseaseDetailsAs.innerHTML = '';
                if (cropNameCd) {
                    fetch(`/admin/disease?crop_name_cd=${cropNameCd}`)
                        .then(response => response.json())
                        .then(data => {
                            const sortedData = Object.entries(data).sort((a, b) => {
                                return a[1].localeCompare(b[
                                    1]); // Sort based on crop name (value)
                            });

                            sortedData.forEach(([key, value]) => {
                                const option = document.createElement('option');
                                option.value = key;
                                option.textContent = value
                                    .toUpperCase();
                                diseaseSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching diseases:', error));
                }
            });




            diseaseSelect.addEventListener('change', function() {
                diseaseCd = this.value;
                tblUser1.style.display = 'none';
                tblUser2.style.display = 'none';
                diseaseDetailsEn.innerHTML = '';
                diseaseDetailsAs.innerHTML = '';

                if (diseaseCd) {
                    fetch(`/admin/crop-disease-symptom?disease_cd=${diseaseCd}&crop_name_cd=${cropNameCd}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Fetched data:', data); // Log the response data for debugging


                            diseaseDetailsEn.innerHTML = '';
                            diseaseDetailsAs.innerHTML = '';


                            if (data.en && data.en.length) {
                                data.en.forEach((item, index) => {
                                    const row = `
                    <tr data-id="${item.id}" data-original-index="${index + 1}">
                        <td>${index + 1}</td>
                        <td style="overflow-wrap: break-word; white-space: normal;">${item.symptom}</td>
                        <td><a href="#" class="text-primary font-weight-bold text-decoration-underline view-images-link" data-item="${item.imagepath1 || ''};${item.imagepath2 || ''};${item.imagepath3 || ''}">View Images</a></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal"
                               data-bs-target="#editModal"
                               data-id="${item.id}"
                               data-disease-cd="${diseaseCd}"
                               data-symptom="${item.symptom}"
                               data-crop-name="${cropNameCd}"
                               data-crop-type-cd="${cropTypeCd}"
                               data-language_cd="${item.language_cd}">
                               <i class="tf-icons bx bx-edit"></i> Edit
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-bs-toggle="modal"
                               data-bs-target="#deleteModal" data-id="${item.id}">
                               <i class="tf-icons bx bx-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    `;
                                    diseaseDetailsEn.insertAdjacentHTML('beforeend', row);
                                });
                            } else {
                                console.log('No English symptoms found for the selected disease');
                                diseaseDetailsEn.innerHTML =
                                    '<tr><td colspan="3" class="text-center">No English symptoms found for the selected disease.</td></tr>';
                            }

                            if (data.as && data.as.length) {
                                data.as.forEach((item, index) => {
                                    const row = `
                    <tr data-id="${item.id}" data-original-index="${index + 1}">
                        <td>${index + 1}</td>
                        <td style="overflow-wrap: break-word; white-space: normal;">${item.symptom}</td>
                          <td><a href="#" class="text-primary font-weight-bold text-decoration-underline view-images-link" data-item="${item.imagepath1 || ''};${item.imagepath2 || ''};${item.imagepath3 || ''}">View Images</a></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal"
                               data-bs-target="#editModal"
                               data-id="${item.id}"
                               data-disease-cd="${diseaseCd}"
                               data-symptom="${item.symptom}"
                               data-crop-name="${cropNameCd}"
                               data-crop-type-cd="${cropTypeCd}"
                               data-language_cd="${item.language_cd}">
                               <i class="tf-icons bx bx-edit"></i> Edit
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-bs-toggle="modal"
                               data-bs-target="#deleteModal" data-id="${item.id}">
                               <i class="tf-icons bx bx-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    `;
                                    diseaseDetailsAs.insertAdjacentHTML('beforeend', row);
                                });
                            } else {
                                console.log('No Assamese symptoms found for the selected disease');
                                '<tr><td colspan="3" class="text-center">No Assamese symptoms found for the selected disease.</td></tr>';
                            }


                            tblUser1.style.display = 'table';
                            tblUser2.style.display = 'table';


                            $('#tblUser1').DataTable();
                            $('#tblUser2').DataTable();
                        })
                        .catch(error => console.error('Error fetching disease symptoms:', error));
                }



            });


            $('#editModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget); // Button that triggered the modal

                const cropTypeCd = button.data('crop-type-cd'); // Extract info from data-* attributes
                const cropNameCd = button.data('crop-name'); // Get the selected crop name
                const diseaseCd = button.data('disease-cd');
                const symptom = button.data('symptom');
                const id = button.data('id');
                const language_cd = button.data('language_cd');


                const modal = $(this);
                modal.find('#crop_type_id').val(cropTypeCd);
                modal.find('#symptom').val(symptom);
                modal.find('#id').val(id);
                modal.find('#language_cd').val(language_cd);


                fetchCropNames(cropTypeCd, cropNameCd);
                fetchDiseases(cropNameCd, diseaseCd);
            });

            $('#editModal').on('change', '#crop_name_id', function() {
                const selectedCropNameCd = $(this).val(); // Get the selected crop name code
                fetchDiseases(selectedCropNameCd, null); // Fetch diseases based on selected crop name
            });

            $('#editModal').on('change', '#crop_type_id', function() {
                const selectedCropTypeCd = $(this).val(); // Get the selected crop type code
                fetchCropNames(selectedCropTypeCd,
                    null);
            });

            function fetchDiseases(cropNameCd, diseaseCd) {
                fetch(`/admin/disease?crop_name_cd=${cropNameCd}`)
                    .then(response => response.json())
                    .then(data => {
                        populateDiseaseSelect(data, diseaseCd);
                    })
                    .catch(error => console.error('Error fetching diseases:', error));
            }

            // New function to populate disease dropdown
            function populateDiseaseSelect(data, diseaseCd) {
                const diseaseSelect = $('#editModal').find('#disease_id');
                diseaseSelect.empty();
                diseaseSelect.append('<option value="">Select Disease</option>');
                const sortedEntries = Object.entries(data)
                    .map(([key, value]) => [key, value.toUpperCase()]) // Convert to uppercase
                    .sort((a, b) => a[1].localeCompare(b[1])); // Sort alphabetically by value

                // Append sorted options to the select element
                sortedEntries.forEach(([key, value]) => {
                    diseaseSelect.append(new Option(value, key));
                });

                if (diseaseCd && data.hasOwnProperty(diseaseCd)) {
                    diseaseSelect.val(diseaseCd);
                } else {
                    console.warn('Disease not found in options:', diseaseCd);
                }
            }

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
                e.preventDefault();


                var isValid = true;
                var symptom = $('#symptom').val().trim();
                var cropTypeCd = $('#crop_type_id').val().trim();
                var cropNameCd = $('#crop_name_id').val().trim();
                var diseaseCd = $('#disease_id').val().trim();
                var form = $(this);

                $('#symptom').removeClass('is-invalid');
                $('#crop_type_id').removeClass('is-invalid');
                $('#crop_name_id').removeClass('is-invalid');
                $('#disease_id').removeClass('is-invalid');
                $('.invalid-feedback').hide();


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

                if (diseaseCd === '') {
                    $('#disease_id').addClass('is-invalid');
                    $('.invalid-feedback.disease-id-feedback').show();
                    isValid = false;
                }

                if (symptom === '') {
                    $('#cropVariety').addClass('is-invalid');
                    $('.invalid-feedback.symptom-feedback').show();
                    isValid = false;
                }

                if (isValid) {
                    $.ajax({
                        url: form.attr('action'),
                        method: 'PUT',
                        data: form.serialize(),
                        dataType: 'json',
                        success: function(response) {

                            if (response.success) {

                                console.log(response);

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

                                const targetRow1 = $(
                                    `#tblUser1 tbody tr[data-id="${response.updatedSymptom.id}"]`
                                    );
                                targetRow1.find('td:nth-child(2)').text(response.updatedSymptom
                                    .symptom);
                                const editButton1 = targetRow1.find('.edit-btn');
                                editButton1.data('id', response.updatedSymptom.id);
                                editButton1.data('disease-cd', response.updatedSymptom
                                    .crop_disease_cd);
                                editButton1.data('symptom', response.updatedSymptom.symptom);
                                editButton1.data('crop-name', response.cropname);
                                editButton1.data('crop-type-cd', response.croptype);
                                editButton1.data('language_cd', response.updatedSymptom
                                    .language_cd);

                                targetRow1.data('original-index', response.updatedSymptom.id);
                                var table1 = $('#tblUser1').DataTable();
                                table1.draw(false);
                                updateSerialNumbers(table1);


                                const targetRow2 = $(
                                    `#tblUser2 tbody tr[data-id="${response.updatedSymptom.id}"]`
                                    );

                                targetRow2.find('td:nth-child(2)').text(response.updatedSymptom
                                    .symptom);
                                const editButton2 = targetRow2.find('.edit-btn');
                                editButton2.data('id', response.updatedSymptom.id);
                                editButton2.data('disease-cd', response.updatedSymptom
                                    .crop_disease_cd);
                                editButton2.data('symptom', response.updatedSymptom.symptom);
                                editButton2.data('crop-name', response.cropname);
                                editButton2.data('crop-type-cd', response.croptype);
                                editButton2.data('language_cd', response.updatedSymptom
                                    .language_cd);

                                targetRow2.data('original-index', response.updatedSymptom.id);
                                var table2 = $('#tblUser2').DataTable();
                                table2.draw(false);
                                updateSerialNumbers(table2);

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
                var id = button.data('id');

                var modal = $(this);
                modal.find('#deleteId').val(id);
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
                            var rowToDeleteUser1 = $('#tblUser1 tbody tr[data-id="' + response
                                .id + '"]');
                            var tableUser1 = $('#tblUser1').DataTable();
                            tableUser1.row(rowToDeleteUser1).remove().draw(false);

                            // Remove from tblUser2
                            var rowToDeleteUser2 = $('#tblUser2 tbody tr[data-id="' + response
                                .id + '"]');
                            var tableUser2 = $('#tblUser2').DataTable();
                            tableUser2.row(rowToDeleteUser2).remove().draw(false);

                            // Redraw both tables
                            tableUser1.draw(false);
                            tableUser2.draw(false);

                            updateSerialNumbers(tableUser1);
                            updateSerialNumbers(tableUser2);

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
                console.log('Updating serial numbers for table:', table);
                table.rows().every(function(index) {
                    var row = this.node();
                    var serialNumber = index + 1;
                    $(row).find('td:first').text(serialNumber);
                });
            }

                 diseaseDetailsEn.addEventListener('click', function(e) {
                    if (e.target && e.target.matches('a.view-images-link')) {
                    try {

                        const item = e.target.getAttribute('data-item');


                        const imagePaths = item.split(';');



                        const image1 = imagePaths[0] ||
                            ''; // First image path (empty string if not available)
                        const image2 = imagePaths[1] ||
                            ''; // Second image path (empty string if not available)
                        const image3 = imagePaths[2] ||
                            ''; // Third image path (empty string if not available)




                        const modalElement = document.getElementById(
                            'imageModal');

                        modalElement.addEventListener('hidden.bs.modal',
                            function() {
                                // Reset the body's overflow property to allow scrolling after modal is hidden
                                document.body.style.overflow =
                                    'auto'; // Allow scrolling
                            });
                        const existingModal = bootstrap.Modal.getInstance(
                            modalElement);
                        if (existingModal) {
                            existingModal
                                .dispose(); // Dispose of the existing modal instance
                        }

                        // Reinitialize the modal instance
                        const newImageModal = new bootstrap.Modal(
                            modalElement);


                        // Set the images in the modal
                        document.getElementById('modalImage1').src =
                            image1 || '';
                        document.getElementById('modalImage2').src =
                            image2 || '';
                        document.getElementById('modalImage3').src =
                            image3 || '';



                        if (newImageModal) {
                            newImageModal.show(); // Show the modal
                        }
                    } catch (error) {
                        console.error('Error parsing item:', error);
                    }
                }
            });

                diseaseDetailsAs.addEventListener('click', function(e) {
                    if (e.target && e.target.matches('a.view-images-link')) {
                    try {

                        const item = e.target.getAttribute('data-item');


                        const imagePaths = item.split(';');



                        const image1 = imagePaths[0] ||
                            ''; // First image path (empty string if not available)
                        const image2 = imagePaths[1] ||
                            ''; // Second image path (empty string if not available)
                        const image3 = imagePaths[2] ||
                            ''; // Third image path (empty string if not available)




                        const modalElement = document.getElementById(
                            'imageModal');

                        modalElement.addEventListener('hidden.bs.modal',
                            function() {
                                // Reset the body's overflow property to allow scrolling after modal is hidden
                                document.body.style.overflow =
                                    'auto'; // Allow scrolling
                            });
                        const existingModal = bootstrap.Modal.getInstance(
                            modalElement);
                        if (existingModal) {
                            existingModal
                                .dispose(); // Dispose of the existing modal instance
                        }

                        // Reinitialize the modal instance
                        const newImageModal = new bootstrap.Modal(
                            modalElement);


                        // Set the images in the modal
                        document.getElementById('modalImage1').src =
                            image1 || '';
                        document.getElementById('modalImage2').src =
                            image2 || '';
                        document.getElementById('modalImage3').src =
                            image3 || '';



                        if (newImageModal) {
                            newImageModal.show(); // Show the modal
                        }
                    } catch (error) {
                        console.error('Error parsing item:', error);
                    }
                }
            });
        });
    </script>
@endsection
