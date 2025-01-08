@extends('admin.common.layout')

@section('title', 'Create Suitability')

@section('custom_header')
@endsection

@section('main')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add Symptom</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cropprotectiondetails.createprotection') }}" method="POST" autocomplete="off">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="crop_type_cd">Crop Type</label>
                    <select class="form-select @error('crop_type_cd') is-invalid @enderror" id="crop_type_cd" name="crop_type_cd">
                        <option value="">Select Crop Type</option>
                        @foreach ($cropTypes as $id => $desc)
                            <option value="{{ $id }}" {{ old('crop_type_cd') == $id ? 'selected' : '' }}>{{ $desc }}</option>
                        @endforeach
                    </select>
                    @error('crop_type_cd')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_name_cd">Crop Name</label>
                    <select class="form-select @error('crop_name_cd') is-invalid @enderror" id="crop_name_cd" name="crop_name_cd">
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
                        data: { crop_type_cd: cropTypeCd },
                        success: function(data) {
                            var cropNameSelect = $('#crop_name_cd');
                            cropNameSelect.empty();
                            cropNameSelect.append('<option value="">Select Crop Name</option>');
                            $.each(data, function(key, value) {
                                cropNameSelect.append('<option value="' + key + '">' + value + '</option>');
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
                        data: { crop_name_cd: cropNameCd },
                        success: function(data) {
                            var diseaseSelect = $('#disease_cd');
                            diseaseSelect.empty();
                            diseaseSelect.append('<option value="">Select Disease</option>');
                            $.each(data, function(key, value) {
                                diseaseSelect.append('<option value="' + key + '">' + value + '</option>');
                            });
                        }
                    });
                } else {
                    $('#disease_cd').empty().append('<option value="">Select Disease</option>');
                }
            });
        });
    </script>
@endsection
