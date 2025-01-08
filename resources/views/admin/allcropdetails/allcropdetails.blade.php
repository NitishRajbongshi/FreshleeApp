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
                                <div class="col-md-4">
                                    <h6>Suitability</h6>
                                    <div class="mb-3">
                                        <label for="soil" class="form-label">Soil</label>
                                        <textarea class="form-control" id="soil" name="soil" rows="4"></textarea>
                                        <div class="invalid-feedback soil-feedback" style="display: none;">Please provide
                                            Soil</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="soil_as" class="form-label">Soil (Assamese)</label>
                                        <textarea class="form-control" rows="4" id="soil_as" name="soil_as"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sowing_time" class="form-label">Sowing Time</label>
                                        <textarea class="form-control" rows="4" id="sowing_time" name="sowing_time"></textarea>
                                        <div class="invalid-feedback sowing-feedback" style="display: none;">Please provide
                                            Sowing Time</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sowing_time_as" class="form-label">Sowing Time (Assamese)</label>
                                        <textarea class="form-control" rows="4" id="sowing_time_as" name="sowing_time_as"></textarea>
                                    </div>
                                </div>

                                <!-- Preparation Section -->
                                <div class="col-md-4">
                                    <h6>Preparation</h6>
                                    <div class="mb-3">
                                        <label for="field_prep" class="form-label">Field Preparation</label>
                                        <textarea class="form-control" rows="4" id="field_prep" name="field_prep"></textarea>
                                        <div class="invalid-feedback field-feedback" style="display: none;">Please provide
                                            Field Preparation</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="field_prep_as" class="form-label">Field Preparation (Assamese)</label>
                                        <textarea class="form-control" rows="4" id="field_prep_as" name="field_prep_as"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="seed_treatment" class="form-label">Seed Treatment</label>
                                        <textarea class="form-control" rows="4" id="seed_treatment" name="seed_treatment"></textarea>
                                        <div class="invalid-feedback seed-treatment-feedback" style="display: none;">
                                            Please provide Seed Treatment</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="seed_treatment_as" class="form-label">Seed Treatment
                                            (Assamese)</label>
                                        <textarea class="form-control" rows="4" id="seed_treatment_as" name="seed_treatment_as"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="seed_rate" class="form-label">Seed Rate</label>
                                        <textarea class="form-control" rows="4" id="seed_rate" name="seed_rate"></textarea>
                                        <div class="invalid-feedback seed-rate-feedback" style="display: none;">Please
                                            provide Seed Rate</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="seed_rate_as" class="form-label">Seed Rate (Assamese)</label>
                                        <textarea class="form-control" rows="4" id="seed_rate_as" name="seed_rate_as"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="spacing" class="form-label">Spacing</label>
                                        <textarea class="form-control" rows="4" id="spacing" name="spacing"></textarea>
                                        <div class="invalid-feedback spacing-feedback" style="display: none;">Please
                                            provide Spacing</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="spacing_as" class="form-label">Spacing (Assamese)</label>
                                        <textarea class="form-control" rows="4" id="spacing_as" name="spacing_as"></textarea>
                                    </div>
                                </div>

                                <!-- Nurturing Section -->
                                <div class="col-md-4">
                                    <h6>Nurturing</h6>
                                    <div class="mb-3">
                                        <label for="irrigation" class="form-label">Irrigation</label>
                                        <textarea class="form-control" rows="4" id="irrigation" name="irrigation"></textarea>
                                        <div class="invalid-feedback irrigation-feedback" style="display: none;">Please
                                            provide Irrigation details</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="irrigation_as" class="form-label">Irrigation (Assamese)</label>
                                        <textarea class="form-control" rows="4" id="irrigation_as" name="irrigation_as"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="weeding" class="form-label">Weeding</label>
                                        <textarea class="form-control" rows="4" id="weeding" name="weeding"></textarea>
                                        <div class="invalid-feedback weeding-feedback" style="display: none;">Please
                                            provide Weeding details</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="weeding_as" class="form-label">Weeding (Assamese)</label>
                                        <textarea class="form-control" rows="4" id="weeding_as" name="weeding_as"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="precuations" class="form-label">Precautions</label>
                                        <textarea class="form-control" rows="4" id="precuations" name="precuations"></textarea>
                                        <div class="invalid-feedback precuations-feedback" style="display: none;">Please
                                            provide Precautions</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="precuations_as" class="form-label">Precautions (Assamese)</label>
                                        <textarea class="form-control" rows="4" id="precuations_as" name="precuations_as"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row px-4 py-2" id="buttonContainer" style="display:none;">
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" id="cancelButton" class="btn btn-warning">Cancel</button>
                                <button type="submit" id="submitButton" class="btn btn-primary">Submit</button>
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
            const allElements = document.querySelectorAll('*');
                  allElements.forEach(el => {
                      el.style.fontSize = '14px';
                  });
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
                            // Convert the data (which is an object) into an array of objects to sort
                            const sortedData = Object.entries(data).sort((a, b) => {
                                // a[1] is the crop name description (value)
                                return a[1].localeCompare(b[
                                1]); // Compare crop names alphabetically
                            });

                            // Now, add the sorted options to the dropdown
                            sortedData.forEach(([key, value]) => {
                                const capitalizedValue = value.toUpperCase();
                                const option = document.createElement('option');
                                option.value = key;
                                option.textContent = capitalizedValue;
                                cropNameSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching crop names:', error);
                        });
                }
            });


            cropNameSelect.addEventListener('change', function() {
                const cropNameCd = this.value;
                document.getElementById('crop_name_cd_input').value = cropNameCd;
                cardContainer.style.display = 'none';
                detailsForm.style.display = 'none';
                buttonContainer.style.display = 'none';

                clearFormFields();

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

                let valid = true;

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

        $(document).ready(function() {
            $('#submitButton').on('click', function(e) {
                e.preventDefault(); // Prevent the form's default submission


                $.ajax({
                    url: $('#combinedForm').attr('action'), // Get form action URL
                    type: 'POST',
                    data: $('#combinedForm').serialize(),
                    success: function(response) {
                        // Check if the success message exists
                        if (response.success) {
                            var popupOverlay = document.createElement('div');
                            popupOverlay.id = 'popupOverlay';
                            popupOverlay.style.position = 'fixed';
                            popupOverlay.style.top = '0';
                            popupOverlay.style.left = '0';
                            popupOverlay.style.width = '100%';
                            popupOverlay.style.height = '100%';
                            popupOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                            popupOverlay.style.display = 'flex';
                            popupOverlay.style.justifyContent = 'center';
                            popupOverlay.style.alignItems = 'center';

                            // Create the popup box
                            var popupBox = document.createElement('div');
                            popupBox.id = 'popupBox';
                            popupBox.style.backgroundColor = 'white';
                            popupBox.style.padding = '20px';
                            popupBox.style.borderRadius = '10px';
                            popupBox.style.textAlign = 'center';
                            popupBox.style.maxWidth = '400px';
                            popupBox.style.width = '80%';
                            popupBox.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';

                            // Add the success message
                            var message = document.createElement('p');
                            message.innerText = response.success;
                            message.style.fontSize = '16px';
                            message.style.fontWeight = 'bold';
                            popupBox.appendChild(message);

                            // Create the "Close" button
                            var closeButton = document.createElement('button');
                            closeButton.innerText = 'Close';
                            closeButton.style.marginTop = '15px';
                            closeButton.style.padding = '10px 20px';
                            closeButton.style.backgroundColor = 'green';
                            closeButton.style.color = 'white';
                            closeButton.style.border = 'none';
                            closeButton.style.borderRadius = '5px';
                            closeButton.style.cursor = 'pointer';
                            closeButton.onclick = function() {
                                // Close the popup when the button is clicked
                                document.body.removeChild(popupOverlay);
                            };

                            popupBox.appendChild(closeButton);
                            popupOverlay.appendChild(popupBox);
                            document.body.appendChild(popupOverlay);

                            // Optionally, you can close the popup after a delay
                            setTimeout(function() {
                                document.body.removeChild(popupOverlay);
                            }, 3000);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText); // Log errors, if any
                    }
                });
            });
        });
    </script>
@endsection
