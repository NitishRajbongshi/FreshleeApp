@extends('admin.common.layout')

@section('title', 'Create Control Measure')

@section('custom_header')
@endsection

@section('main')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add Symptom</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cropprotectiondetails.createprotection') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
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
                    @error('crop_type_cd')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_name_cd">Crop Name</label>
                    <select class="form-select @error('crop_name_cd') is-invalid @enderror" id="crop_name_cd"
                        name="crop_name_cd">
                        <option value="">Select Crop Name</option>
                    </select>
                    @error('crop_name_cd')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="disease_cd" class="form-label">Disease</label>
                    <select class="form-select @error('disease_cd') is-invalid @enderror" id="disease_cd" name="disease_cd">
                        <option value="">Select Disease</option>
                    </select>
                    @error('disease_cd')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>



                <div class="mb-3">
                    <label class="form-label" for="control_measure">Control Measure</label>
                    <input type="text" class="form-control @error('control_measure') is-invalid @enderror"
                        id="control_measure" name="control_measure" placeholder="Control Measure"
                        value="{{ old('control_measure') }}">
                    @error('control_measure')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="control_measure_as">Control Measure (Assamese)</label>
                    <input type="text" class="form-control @error('control_measure_as') is-invalid @enderror"
                        id="control_measure_as" name="control_measure_as" placeholder="Control Measure in Assamese"
                        value="{{ old('control_measure_as') }}">
                    @error('control_measure_as')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>



                <div class="mb-3">
                    <label class="form-label" for="imagepath1">Symptom Image 1</label>
                    <input type="file" class="form-control @error('imagepath1') is-invalid @enderror"
                        id="imagepath1" name="imagepath1" accept="image/*" onchange="previewImage(1)">
                    @error('imagepath1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <!-- Preview for Image 1 -->
                    <div id="image_preview_1" class="mt-2">
                        <img src="#" alt="Symptom Image 1 Preview" class="img-thumbnail" style="display: none;">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="imagepath2">Symptom Image 2</label>
                    <input type="file" class="form-control @error('imagepath2') is-invalid @enderror"
                        id="imagepath2" name="imagepath2" accept="image/*" onchange="previewImage(2)">
                    @error('imagepath2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <!-- Preview for Image 2 -->
                    <div id="image_preview_2" class="mt-2">
                        <img src="#" alt="Symptom Image 2 Preview" class="img-thumbnail" style="display: none;">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="imagepath3">Symptom Image 3</label>
                    <input type="file" class="form-control @error('imagepath3') is-invalid @enderror"
                        id="imagepath3" name="imagepath3" accept="image/*" onchange="previewImage(3)">
                    @error('imagepath3')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                            $.each(data, function(key, value) {
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
                            $.each(data, function(key, value) {
                                diseaseSelect.append('<option value="' + key + '">' +
                                    value + '</option>');
                            });
                        }
                    });
                } else {
                    $('#disease_cd').empty().append('<option value="">Select Disease</option>');
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
