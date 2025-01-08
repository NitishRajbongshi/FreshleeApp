@extends('admin.common.layout')

@section('title', 'Create Suitability')

@section('custom_header')
@endsection

@section('main')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add Suitability</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cropvarietydetails.createvariety') }}" method="POST" autocomplete="off">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="crop_type_cd">Crop Type</label>
                    <select class="form-select @error('crop_type_cd') is-invalid @enderror" id="crop_type_cd"
                        name="crop_type_cd">
                        <option value="">Select Crop Type</option>
                        @foreach ($cropTypes as $id => $desc)
                            <option value="{{ $id }}" {{ old('crop_type_cd') == $id ? 'selected' : '' }}>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </select>
                    @error('crop_type_cd')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_name_cd">Crop Name</label>
                    <select class="form-select @error('crop_name_cd') is-invalid @enderror" id="crop_name_cd"
                        name="crop_name_cd">
                        <option value="">Select Crop Name</option>
                        <!-- Crop names will be populated here via JavaScript -->
                    </select>
                    @error('crop_name_cd')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_variety_desc">Crop Variety</label>
                    <input type="text" class="form-control @error('crop_variety_desc') is-invalid @enderror"
                        id="crop_variety_desc" name="crop_variety_desc" placeholder="Crop Variey"
                        value="{{ old('crop_variety_desc') }}">
                    @error('crop_variety_desc')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_variety_desc_ass">Crop Variety (Assamese)</label>
                    <input type="text" class="form-control"
                        id="crop_variety_desc_as" name="crop_variety_desc_as" placeholder="Crop Variety in Assamese"
                        value="{{ old('crop_variety_desc_as') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_variety_dtls">Crop Variety Details</label>
                    <input type="text" class="form-control @error('crop_variety_dtls') is-invalid @enderror"
                        id="crop_variety_dtls" name="crop_variety_dtls" placeholder="Crop Variety Details"
                        value="{{ old('crop_variety_dtls') }}">
                    @error('crop_variety_dtls')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_variety_dtls_as">Crop Variety Details (Assamese)</label>
                    <input type="text" class="form-control"
                        id="crop_variety_dtls_as" name="crop_variety_dtls_as" placeholder="Crop Variety Details in Assamese"
                        value="{{ old('crop_variety_dtls_as') }}">
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('admin.cropvarietydetails') }}" class="btn btn-warning">Cancel</a>
            </form>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
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
                            cropNameSelect.empty(); // Clear existing options
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
        });
    </script>
@endsection
