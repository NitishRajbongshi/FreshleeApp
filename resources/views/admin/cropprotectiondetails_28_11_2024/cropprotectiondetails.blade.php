@extends('admin.common.layout')

@section('title', 'All Crop Variety Management')

@section('main')
    @if ($message = Session::get('success'))
        <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">All Crop Control Measure Management</h5>
            <div>
                <a href="{{ route('admin.cropprotectiondetails.createprotection') }}" class="btn btn-outline-success">
                    <i class="tf-icons bx bx-plus-medical"></i>Add Control Measure
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

            <div class="table-responsive text-nowrap px-4">
                <table class="table" id="tblUser" style="display:none;">
                    <thead>
                        <tr>
                            <th>Sl. No.</th>
                            <th>Control Measure</th>
                            <th>Control Measure Assamese</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0" id="diseaseDetails">

                    </tbody>
                </table>
            </div>


            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Control Measure</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editForm" method="POST"
                            action="{{ route('admin.cropprotectiondetails.cropprotectiondetails.update') }}"
                            autocomplete="off">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="col mb-3">
                                    <label for="crop_type_id" class="form-label">Select Crop Type</label>
                                    <select class="form-select @error('crop_type_id') is-invalid @enderror"
                                        id="crop_type_id" name="crop_type_cd">
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

                                <input type="hidden" name="mapping_id" id="mapping_id">

                                <div class="mb-3">
                                    <label for="controlMeasureDetails" class="form-label">Control Measure</label>
                                    <input type="text" class="form-control" id="controlMeasureDetails"
                                        name="control_measure" placeholder="Enter Control Measure">
                                    <div class="invalid-feedback controlMeasureDetails-feedback" style="display: none;">
                                        Please provide Control Measure
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="controlMeasureDetailsAs" class="form-label">Control Measure
                                        Assamese</label>
                                    <input type="text" class="form-control" id="controlMeasureDetailsAs"
                                        name="control_measure_as" placeholder="Enter Control Measure (Assamese)">
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
                            <h5 class="modal-title" id="deleteModalLabel">Delete Control Measure</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="deleteForm" method="POST" action="{{ route('admin.cropprotectiondetails.cropprotectiondetails.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                <p>Are you sure you want to delete this Control Measure?</p>
                                <input type="hidden" name="mapping_id" id="mapping_id">
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

            const cropTypeSelect = document.getElementById('crop_type_cd');
            const cropNameSelect = document.getElementById('crop_name_cd');
            const diseaseSelect = document.getElementById('disease_cd');
            const tblUser = document.getElementById('tblUser');
            const diseaseDetails = document.getElementById('diseaseDetails');
            let cropTypeCd = '';
            let cropNameCd = '';
            let diseaseCd = '';

            cropTypeSelect.addEventListener('change', function() {
                cropTypeCd = this.value;
                cropNameSelect.innerHTML = '<option value="">Select Crop Name</option>';
                diseaseSelect.innerHTML =
                    '<option value="">Select Disease</option>'; // Clear diseases when crop type is changed
                tblUser.style.display = 'none';
                diseaseDetails.innerHTML = '';
                if (cropTypeCd) {
                    fetch(`/admin/crop-names?crop_type_cd=${cropTypeCd}`)
                        .then(response => response.json())
                        .then(data => {
                            for (const [key, value] of Object.entries(data)) {
                                const option = document.createElement('option');
                                option.value = key;
                                option.textContent = value;
                                cropNameSelect.appendChild(option);
                            }
                        })
                        .catch(error => console.error('Error fetching crop names:', error));
                }
            });

            cropNameSelect.addEventListener('change', function() {
                cropNameCd = this.value;
                diseaseSelect.innerHTML =
                    '<option value="">Select Disease</option>'; // Reset disease dropdown
                tblUser.style.display = 'none';
                diseaseDetails.innerHTML = '';
                if (cropNameCd) {
                    fetch(`/admin/diseases?crop_name_cd=${cropNameCd}`)
                        .then(response => response.json())
                        .then(data => {
                            for (const [key, value] of Object.entries(data)) {
                                const option = document.createElement('option');
                                option.value = key;
                                option.textContent = value;
                                diseaseSelect.appendChild(option);
                            }
                        })
                        .catch(error => console.error('Error fetching diseases:', error));
                }
            });




            diseaseSelect.addEventListener('change', function() {
                diseaseCd = this.value;
                tblUser.style.display = 'none';
                diseaseDetails.innerHTML = '';

                if (diseaseCd) {
                    fetch(`/admin/crop-disease?disease_cd=${diseaseCd}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Disease details data:', data); // Debugging line
                            if (data.length) {
                                data.forEach((item, index) => {
                                    const row = `
<tr>
                                        <td>${index + 1}</td>
                                        <td style="overflow-wrap: break-word; white-space: normal;">${item.control_measure}</td>
                                        <td style="overflow-wrap: break-word; white-space: normal;">${item.control_measure_as}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                data-mapping_id="${item.mapping_id}"
                                                data-disease-cd="${diseaseCd}"
                                                 data-crop-type-cd="${cropTypeCd}"
                                                data-crop-name="${cropNameCd}"
                                                data-control-measure="${item.control_measure}"
                                                data-control-measure-as="${item.control_measure_as}"
                                              >
                                                <i class="tf-icons bx bx-edit"></i> Edit
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal"
                                                data-mapping_id="${item.mapping_id}"
                                                >
                                                <i class="tf-icons bx bx-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                `;
                                    diseaseDetails.insertAdjacentHTML('beforeend', row);
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
                const diseaseCd = button.data('disease-cd');
                const controlMeasure = button.data('control-measure'); // Get variety description
                const controlMeasureAs = button.data('control-measure-as');
                const mapping_id = button.data('mapping_id');


                const modal = $(this);
                modal.find('#crop_type_id').val(cropTypeCd);
                modal.find('#mapping_id').val(mapping_id);
                modal.find('#controlMeasureDetails').val(controlMeasure);
                modal.find('#controlMeasureDetailsAs').val(controlMeasureAs);


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
                Object.entries(data).forEach(([key, value]) => {
                    diseaseSelect.append(new Option(value, key));
                });

                if (diseaseCd && data.hasOwnProperty(diseaseCd)) {
                    diseaseSelect.val(diseaseCd);
                } else {
                    console.warn('Disease not found in options:', diseaseCd);
                }
            }

            function fetchCropNames(cropTypeCd, cropNameCd) {
                fetch(`/admin/crop-nam?crop_type_cd=${cropTypeCd}`)
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
                Object.entries(data).forEach(([key, value]) => {
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
                var cropTypeCd = $('#crop_type_id').val().trim();
                var cropNameCd = $('#crop_name_id').val().trim();
                var diseaseCd = $('#disease_id').val().trim();
                var controlMeasure = $('#controlMeasureDetails').val().trim();
                var controlMeasureAs = $('#controlMeasureDetailsAs').val().trim();
                var form = $(this);


                $('#crop_type_id').removeClass('is-invalid');
                $('#crop_name_id').removeClass('is-invalid');
                $('#disease_id').removeClass('is-invalid');
                $('#controlMeasureDetails').removeClass('is-invalid');
                $('.invalid-feedback').hide();

                console.log(cropNameCd);
                console.log(cropTypeCd);
                console.log(diseaseCd);


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

                if (controlMeasure === '') {
                    $('#controlMeasureDetails').addClass('is-invalid');
                    $('.invalid-feedback.controlMeasureDetails-feedback').show();
                    isValid = false;
                }

                if (isValid) {
                    form.off('submit').submit();
                }
            });


            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var mapping_id = button.data('mapping_id');

                var modal = $(this);
                modal.find('#mapping_id').val(mapping_id);
            });

            // Handle form submission for deletion
            $('#deleteForm').on('submit', function(e) {
                e.preventDefault();

                var form = $(this);

                $.ajax({
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        form.off('submit').submit();
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                    }
                });
            });




        });
    </script>
@endsection
