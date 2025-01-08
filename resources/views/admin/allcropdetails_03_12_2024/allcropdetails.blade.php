@extends('admin.common.layout')

@section('title', 'All Crop Management')

@section('main')
    @if ($message = Session::get('success'))
        <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="d-flex align-items-center">
            <h5 class="card-header">All Crop Management</h5>
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
                    <!-- Crop names will be populated here -->
                </select>
            </div>
        </div>

        <div class="container">
            <form id="combinedForm" action="{{ route('admin.allcropdetails.allcropdetails.saveSuitabilityDetails') }}"
                method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="crop_type_cd" id="crop_type_cd_input" value="">
                <input type="hidden" name="crop_name_cd" id="crop_name_cd_input" value="">

                <div class="row px-4 py-2" id="cardContainer" style="display:none;">
                    <div class="col-md-12">
                        <div class="card text-center" id="addDetailsCard"
                            style="background-color: #ffe5b4; cursor: pointer;">
                            <div class="card-body">
                                <i class="tf-icons bx bx-plus-medical"></i>
                                <h6 class="card-title mt-2">Add Details</h6>
                            </div>
                        </div>
                        <div class="form-container mt-3" id="detailsForm" style="display:none;">
                            <div class="row">
                                <!-- Suitability Section -->
                                <div class="col-md-4">
                                    <h6>Suitability</h6>
                                    <div class="mb-3">
                                        <label for="soil" class="form-label">Soil</label>
                                        <input type="text" class="form-control" id="soil" name="soil">
                                        <div class="invalid-feedback soil-feedback" style="display: none;">Please provide
                                            Soil</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="soil_as" class="form-label">Soil (Assamese)</label>
                                        <input type="text" class="form-control" id="soil_as" name="soil_as">
                                    </div>
                                    <div class="mb-3">
                                        <label for="sowing_time" class="form-label">Sowing Time</label>
                                        <input type="text" class="form-control" id="sowing_time" name="sowing_time">
                                        <div class="invalid-feedback sowing-feedback" style="display: none;">Please provide
                                            Sowing Time</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sowing_time_as" class="form-label">Sowing Time (Assamese)</label>
                                        <input type="text" class="form-control" id="sowing_time_as"
                                            name="sowing_time_as">
                                    </div>
                                </div>

                                <!-- Preparation Section -->
                                <!-- Preparation Section -->
                                <div class="col-md-4">
                                    <h6>Preparation</h6>
                                    <div class="mb-3">
                                        <label for="field_prep" class="form-label">Field Preparation</label>
                                        <input type="text" class="form-control" id="field_prep" name="field_prep">
                                        <div class="invalid-feedback field-feedback" style="display: none;">Please provide
                                            Field Preparation</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="field_prep_as" class="form-label">Field Preparation (Assamese)</label>
                                        <input type="text" class="form-control" id="field_prep_as" name="field_prep_as">
                                    </div>
                                    <div class="mb-3">
                                        <label for="seed_treatment" class="form-label">Seed Treatment</label>
                                        <input type="text" class="form-control" id="seed_treatment"
                                            name="seed_treatment">
                                        <div class="invalid-feedback seed-treatment-feedback" style="display: none;">
                                            Please provide Seed Treatment</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="seed_treatment_as" class="form-label">Seed Treatment
                                            (Assamese)</label>
                                        <input type="text" class="form-control" id="seed_treatment_as"
                                            name="seed_treatment_as">
                                    </div>
                                    <div class="mb-3">
                                        <label for="seed_rate" class="form-label">Seed Rate</label>
                                        <input type="text" class="form-control" id="seed_rate" name="seed_rate">
                                        <div class="invalid-feedback seed-rate-feedback" style="display: none;">Please
                                            provide Seed Rate</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="seed_rate_as" class="form-label">Seed Rate (Assamese)</label>
                                        <input type="text" class="form-control" id="seed_rate_as"
                                            name="seed_rate_as">
                                    </div>
                                    <div class="mb-3">
                                        <label for="spacing" class="form-label">Spacing</label>
                                        <input type="text" class="form-control" id="spacing" name="spacing">
                                        <div class="invalid-feedback spacing-feedback" style="display: none;">Please
                                            provide Spacing</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="spacing_as" class="form-label">Spacing (Assamese)</label>
                                        <input type="text" class="form-control" id="spacing_as" name="spacing_as">
                                    </div>
                                </div>

                                <!-- Nurturing Section -->
                                <div class="col-md-4">
                                    <h6>Nurturing</h6>
                                    <div class="mb-3">
                                        <label for="irrigation" class="form-label">Irrigation</label>
                                        <input type="text" class="form-control" id="irrigation" name="irrigation">
                                        <div class="invalid-feedback irrigation-feedback" style="display: none;">Please
                                            provide Irrigation details</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="irrigation_as" class="form-label">Irrigation (Assamese)</label>
                                        <input type="text" class="form-control" id="irrigation_as"
                                            name="irrigation_as">
                                    </div>
                                    <div class="mb-3">
                                        <label for="weeding" class="form-label">Weeding</label>
                                        <input type="text" class="form-control" id="weeding" name="weeding">
                                        <div class="invalid-feedback weeding-feedback" style="display: none;">Please
                                            provide Weeding details</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="weeding_as" class="form-label">Weeding (Assamese)</label>
                                        <input type="text" class="form-control" id="weeding_as" name="weeding_as">
                                    </div>
                                    <div class="mb-3">
                                        <label for="precuations" class="form-label">Precautions</label>
                                        <input type="text" class="form-control" id="precuations" name="precuations">
                                        <div class="invalid-feedback precuations-feedback" style="display: none;">Please
                                            provide Precautions</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="precuations_as" class="form-label">Precautions (Assamese)</label>
                                        <input type="text" class="form-control" id="precuations_as"
                                            name="precuations_as">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row px-4 py-2" id="buttonContainer" style="display:none;">
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" id="cancelButton" class="btn btn-warning">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_css')
    <style>
        /* Flexbox styling for horizontal sections */
        .row {
            display: flex;
            flex-wrap: nowrap;
            /* Prevents wrapping to a new line */
        }

        .col-md-4 {
            padding: 10px;
            /* Padding around columns */
        }
    </style>
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
            const cardContainer = document.getElementById('cardContainer');
            const addDetailsCard = document.getElementById('addDetailsCard');
            const buttonContainer = document.getElementById('buttonContainer');
            const detailsForm = document.getElementById('detailsForm');
            const form = document.getElementById('combinedForm');
            const soilInput = document.getElementById('soil');
            const fieldPrepInput = document.getElementById('field_prep');
            const seedTreatmentInput = document.getElementById('seed_treatment');
            const seedRateInput = document.getElementById('seed_rate');
            const spacingInput = document.getElementById('spacing');
            const irrigationInput = document.getElementById('irrigation');
            const weedingInput = document.getElementById('weeding');
            const precautionsInput = document.getElementById('precuations');
            const sowingTimeInput = document.getElementById('sowing_time'); // Add this line

            const soilFeedback = document.querySelector('.soil-feedback');
            const fieldFeedback = document.querySelector('.field-feedback');
            const seedTreatmentFeedback = document.querySelector('.seed-treatment-feedback');
            const seedRateFeedback = document.querySelector('.seed-rate-feedback');
            const spacingFeedback = document.querySelector('.spacing-feedback');
            const irrigationFeedback = document.querySelector('.irrigation-feedback');
            const weedingFeedback = document.querySelector('.weeding-feedback');
            const precautionsFeedback = document.querySelector('.precuations-feedback');
            const sowingTimeFeedback = document.querySelector('.sowing-feedback'); // Add this line
            const cancelButton = document.getElementById('cancelButton');

            if (cancelButton) {
                cancelButton.addEventListener('click', function() {
                    location.reload(); // Refresh the page
                });
            }

            const clearFormFields = () => {
                soilInput.value = '';
                fieldPrepInput.value = '';
                seedTreatmentInput.value = '';
                seedRateInput.value = '';
                spacingInput.value = '';
                irrigationInput.value = '';
                weedingInput.value = '';
                precautionsInput.value = '';
                sowingTimeInput.value = '';

                // Clear all feedback messages
                soilFeedback.style.display = 'none';
                fieldFeedback.style.display = 'none';
                seedTreatmentFeedback.style.display = 'none';
                seedRateFeedback.style.display = 'none';
                spacingFeedback.style.display = 'none';
                irrigationFeedback.style.display = 'none';
                weedingFeedback.style.display = 'none';
                precautionsFeedback.style.display = 'none';
                sowingTimeFeedback.style.display = 'none';
            };

            cropTypeSelect.addEventListener('change', function() {
                const cropTypeCd = this.value;
                document.getElementById('crop_type_cd_input').value = cropTypeCd;
                cropNameSelect.innerHTML = '<option value="">Select Crop Name</option>';
                cardContainer.style.display = 'none';
                detailsForm.style.display = 'none';

                clearFormFields(); // Clear fields when crop type changes

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
                        });
                }
            });

            cropNameSelect.addEventListener('change', function() {
                const cropNameCd = this.value;
                document.getElementById('crop_name_cd_input').value = cropNameCd;
                cardContainer.style.display = 'none';
                detailsForm.style.display = 'none';
                buttonContainer.style.display = 'none';

                clearFormFields(); // Clear fields when crop name changes

                if (cropNameCd) {
                    cardContainer.style.display = 'block';

                    // Fetch suitability details
                    fetch(
                            `/admin/suitability-details?crop_type_cd=${cropTypeSelect.value}&crop_name_cd=${cropNameCd}`
                        )
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok: ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.suitability) {
                                document.getElementById('soil').value = data.suitability.soil || '';
                                document.getElementById('soil_as').value = data.suitability.soil_as ||
                                    '';
                                document.getElementById('sowing_time').value = data.suitability
                                    .sowing_time || '';
                                document.getElementById('sowing_time_as').value = data.suitability
                                    .sowing_time_as || '';
                            }

                            if (data.field_preparation) {
                                document.getElementById('field_prep').value = data.field_preparation
                                    .field_prep || '';
                                document.getElementById('field_prep_as').value = data.field_preparation
                                    .field_prep_as || '';
                                document.getElementById('seed_treatment').value = data.field_preparation
                                    .seed_treatment || '';
                                document.getElementById('seed_treatment_as').value = data
                                    .field_preparation.seed_treatment_as || '';
                                document.getElementById('seed_rate').value = data.field_preparation
                                    .seed_rate || '';
                                document.getElementById('seed_rate_as').value = data.field_preparation
                                    .seed_rate_as || '';
                                document.getElementById('spacing').value = data.field_preparation
                                    .spacing || '';
                                document.getElementById('spacing_as').value = data.field_preparation
                                    .spacing_as || '';
                            }

                            if (data.nurturing) {
                                document.getElementById('irrigation').value = data.nurturing
                                    .irrigation || '';
                                document.getElementById('irrigation_as').value = data.nurturing
                                    .irrigation_as || '';
                                document.getElementById('weeding').value = data.nurturing.weeding || '';
                                document.getElementById('weeding_as').value = data.nurturing
                                    .weeding_as || '';
                                document.getElementById('precuations').value = data.nurturing
                                    .precuations || '';
                                document.getElementById('precuations_as').value = data.nurturing
                                    .precuations_as || '';
                            }
                        })

                        .catch(error => {
                            console.error('Error fetching suitability details:', error);
                            // alert('Failed to fetch suitability details. Please try again later.');
                        });
                } else {
                    cardContainer.style.display = 'none';
                    detailsForm.style.display = 'none';
                }
            });

            form.addEventListener('submit', function(event) {
                soilFeedback.style.display = 'none';
                fieldFeedback.style.display = 'none';
                seedTreatmentFeedback.style.display = 'none';
                seedRateFeedback.style.display = 'none';
                spacingFeedback.style.display = 'none';
                irrigationFeedback.style.display = 'none';
                weedingFeedback.style.display = 'none';
                precautionsFeedback.style.display = 'none';
                sowingTimeFeedback.style.display = 'none';

                let valid = true; // Flag to track overall form validity

                // Check if each input is empty and show feedback if it is
                if (!soilInput.value.trim()) {
                    soilFeedback.style.display = 'block';
                    valid = false; // Set valid to false
                }

                if (!fieldPrepInput.value.trim()) {
                    fieldFeedback.style.display = 'block';
                    valid = false; // Set valid to false
                }

                if (!seedTreatmentInput.value.trim()) {
                    seedTreatmentFeedback.style.display = 'block';
                    valid = false; // Set valid to false
                }

                if (!seedRateInput.value.trim()) {
                    seedRateFeedback.style.display = 'block';
                    valid = false; // Set valid to false
                }

                if (!spacingInput.value.trim()) {
                    spacingFeedback.style.display = 'block';
                    valid = false; // Set valid to false
                }

                if (!irrigationInput.value.trim()) {
                    irrigationFeedback.style.display = 'block';
                    valid = false; // Set valid to false
                }

                if (!weedingInput.value.trim()) {
                    weedingFeedback.style.display = 'block';
                    valid = false; // Set valid to false
                }

                if (!precautionsInput.value.trim()) {
                    precautionsFeedback.style.display = 'block';
                    valid = false; // Set valid to false
                }

                if (!sowingTimeInput.value.trim()) { // Add this validation
                    sowingTimeFeedback.style.display = 'block';
                    valid = false; // Set valid to false
                }

                // Prevent form submission if any field is invalid
                if (!valid) {
                    event.preventDefault(); // Prevent form submission
                }
            });

            addDetailsCard.addEventListener('click', function() {
                detailsForm.style.display = detailsForm.style.display === 'none' ? 'block' : 'none';
                buttonContainer.style.display = detailsForm.style.display === 'block' ? 'flex' : 'none';
            });
        });
    </script>
@endsection
