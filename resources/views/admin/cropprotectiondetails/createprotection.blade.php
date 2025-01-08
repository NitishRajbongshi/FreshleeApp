@extends('admin.common.layout')

@section('title', 'Create Control Measure')

@section('custom_header')
@endsection

@section('main')
    @if ($message = Session::get('success'))
        <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add Control Measure</h5>
        </div>
        <div class="card-body">
            <form id="controlMeasureForm" action="{{ route('admin.cropprotectiondetails.createprotection') }}"
                method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="crop_type_cd">Crop Type</label>
                    <select class="form-select @error('crop_type_cd') is-invalid @enderror" id="crop_type_cd"
                        name="crop_type_cd">
                        <option value="">Select Crop Type</option>
                        @foreach ($cropTypes as $id => $desc)
                            <option value="{{ $id }}" {{ old('crop_type_cd') == $id ? 'selected' : '' }}>
                                {{ $desc }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback crop_type_cd-feedback" style="display: none;">
                        Please select a Crop Type.
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_name_cd">Crop Name</label>
                    <select class="form-select @error('crop_name_cd') is-invalid @enderror" id="crop_name_cd"
                        name="crop_name_cd">
                        <option value="">Select Crop Name</option>
                    </select>
                    <div class="invalid-feedback crop_name_cd-feedback" style="display: none;">
                        Please select a Crop Name.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="disease_cd" class="form-label">Disease</label>
                    <select class="form-select @error('disease_cd') is-invalid @enderror" id="disease_cd" name="disease_cd">
                        <option value="">Select Disease</option>
                    </select>
                    <div class="invalid-feedback disease_cd-feedback" style="display: none;">
                        Please select a Disease.
                    </div>
                </div>



                <div class="mb-3">
                    <label class="form-label" for="control_measure">Control Measure</label>
                    <textarea rows="4" class="form-control @error('control_measure') is-invalid @enderror" id="control_measure"
                        name="control_measure" placeholder="Control Measure" value="{{ old('control_measure') }}"></textarea>
                    <div class="invalid-feedback control_measure-feedback" style="display: none;">
                        Please provide Crop measure
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="control_measure_as">Control Measure (Assamese)</label>
                    <textarea rows="4" class="form-control @error('control_measure_as') is-invalid @enderror" id="control_measure_as"
                        name="control_measure_as" placeholder="Control Measure in Assamese" value="{{ old('control_measure_as') }}"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="imagepath1">Symptom Image 1</label>
                    <input type="file" class="form-control @error('imagepath1') is-invalid @enderror" id="imagepath1"
                        name="imagepath1" accept="image/*" onchange="previewImage(1)">
                    <div class="invalid-feedback imagepath1-feedback" style="display: none;">
                        Please provide symptom image 1
                    </div>
                    <div id="image_preview_1" class="mt-2">
                        <img src="#" alt="Symptom Image 1 Preview" class="img-thumbnail" style="display: none;">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="imagepath2">Symptom Image 2</label>
                    <input type="file" class="form-control @error('imagepath2') is-invalid @enderror" id="imagepath2"
                        name="imagepath2" accept="image/*" onchange="previewImage(2)">
                    <div class="invalid-feedback imagepath2-feedback" style="display: none;">
                        Please provide symptom image 2
                    </div>
                    <!-- Preview for Image 2 -->
                    <div id="image_preview_2" class="mt-2">
                        <img src="#" alt="Symptom Image 2 Preview" class="img-thumbnail" style="display: none;">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="imagepath3">Symptom Image 3</label>
                    <input type="file" class="form-control @error('imagepath3') is-invalid @enderror" id="imagepath3"
                        name="imagepath3" accept="image/*" onchange="previewImage(3)">
                    <div class="invalid-feedback imagepath3-feedback" style="display: none;">
                        Please provide symptom image 2
                    </div>
                    <!-- Preview for Image 3 -->
                    <div id="image_preview_3" class="mt-2">
                        <img src="#" alt="Symptom Image 3 Preview" class="img-thumbnail" style="display: none;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('admin.cropprotectiondetails') }}" class="btn btn-warning">Cancel</a>
            </form>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        });

        $(document).ready(function() {

            // Fetch crop names based on crop type selection
            $('#crop_type_cd').change(function() {
                var cropTypeCd = $(this).val();
                if (cropTypeCd) {
                    $.ajax({
                        url: '{{ route('admin.appmaster.getCropNames') }}',
                        type: 'GET',
                        data: {
                            crop_type_cd: cropTypeCd
                        },
                        success: function(data) {
                            var cropNameSelect = $('#crop_name_cd');
                            cropNameSelect.empty();
                            cropNameSelect.append('<option value="">Select Crop Name</option>');

                            var sortedData = Object.entries(data).sort(function(a, b) {
                                return a[1].localeCompare(b[
                                    1]); // Compare crop names alphabetically
                            });

                            // Append sorted crop names to the dropdown
                            $.each(sortedData, function(index, [key, value]) {
                                cropNameSelect.append('<option value="' + key + '">' +
                                    value + '</option>');
                            });

                        }
                    });
                } else {
                    $('#crop_name_cd').empty().append('<option value="">Select Crop Name</option>');
                }
            });

            // Fetch diseases based on crop name selection
            $('#crop_name_cd').change(function() {
                var cropNameCd = $(this).val();
                if (cropNameCd) {
                    $.ajax({
                        url: '{{ route('admin.cropprotectiondetails.getDiseases') }}',
                        type: 'GET',
                        data: {
                            crop_name_cd: cropNameCd
                        },
                        success: function(data) {
                            var diseaseSelect = $('#disease_cd');
                            diseaseSelect.empty();
                            diseaseSelect.append('<option value="">Select Disease</option>');

                            var sortedData = Object.entries(data).sort(function(a, b) {
                                return a[1].localeCompare(b[
                                    1]); // Compare crop names alphabetically
                            });

                            // Append sorted crop names to the dropdown
                            $.each(sortedData, function(index, [key, value]) {
                                diseaseSelect.append('<option value="' + key + '">' +
                                    value.toUpperCase() + '</option>');
                            });
                        }
                    });
                } else {
                    $('#disease_cd').empty().append('<option value="">Select Disease</option>');
                }
            });




            $('#controlMeasureForm').on('submit', function(e) {
                e.preventDefault();
                var isValid = true;


                var cropTypeCd = $('#crop_type_cd').val().trim();
                var cropNameCd = $('#crop_name_cd').val().trim();
                var diseaseCd = $('#disease_cd').val().trim();
                var controlMeasure = $('#control_measure').val().trim();
                var image1 = $('#imagepath1').val().trim();
                var image2 = $('#imagepath2').val().trim();
                var image3 = $('#imagepath3').val().trim();
                var form = $(this);


                $('#crop_type_cd').removeClass('is-invalid');
                $('#crop_name_cd').removeClass('is-invalid');
                $('#disease_cd').removeClass('is-invalid');
                $('#control_measure').removeClass('is-invalid');
                $('#imagepath1').removeClass('is-invalid');
                $('#imagepath2').removeClass('is-invalid');
                $('#imagepath3').removeClass('is-invalid');
                $('.invalid-feedback').hide();


                if (cropTypeCd === '') {
                    $('#crop_type_cd').addClass('is-invalid');
                    $('.invalid-feedback.crop_type_cd-feedback').show();
                    isValid = false;
                }

                if (cropNameCd === '') {
                    $('#crop_name_cd').addClass('is-invalid');
                    $('.invalid-feedback.crop_name_cd-feedback').show();
                    isValid = false;
                }

                if (diseaseCd === '') {
                    $('#disease_cd').addClass('is-invalid');
                    $('.invalid-feedback.disease_cd-feedback').show();
                    isValid = false;
                }

                if (controlMeasure === '') {
                    $('#control_measure').addClass('is-invalid');
                    $('.invalid-feedback.control_measure-feedback').show();
                    isValid = false;
                }

                if (image1 === '') {
                    $('#imagepath1').addClass('is-invalid');
                    $('.invalid-feedback.imagepath1-feedback').show();
                    isValid = false;
                }

                if (image2 === '') {
                    $('#imagepath2').addClass('is-invalid');
                    $('.invalid-feedback.imagepath2-feedback').show();
                    isValid = false;
                }

                if (image3 === '') {
                    $('#imagepath3').addClass('is-invalid');
                    $('.invalid-feedback.imagepath3-feedback').show();
                    isValid = false;
                }

                if (isValid) {
                    this.submit();
                }

            });
        });

        function previewImage(imageNumber) {
            var input = document.getElementById('imagepath' + imageNumber);
            var preview = document.getElementById('image_preview_' + imageNumber);
            var file = input.files[0];

            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = preview.querySelector('img');
                    img.style.display = 'block';
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                var img = preview.querySelector('img');
                img.style.display = 'none';
            }
        }
    </script>
@endsection
