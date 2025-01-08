@extends('admin.common.layout')

@section('title', 'Create Suitability')

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
            <h5 class="mb-0">Add Suitability</h5>
        </div>

        <div class="card-body">
            <form id="cropSuitabilityForm" action="{{ route('admin.appmaster.createsuitability') }}" method="POST" autocomplete="off">
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
                    <div class="invalid-feedback crop_type_cd-feedback" style="display: none;">
                        Please provide Crop Type
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_name_cd">Crop Name</label>
                    <select class="form-select @error('crop_name_cd') is-invalid @enderror" id="crop_name_cd"
                        name="crop_name_cd">
                        <option value="">Select Crop Name</option>
                        <!-- Crop names will be populated here via JavaScript -->
                    </select>
                    <div class="invalid-feedback crop_name_cd-feedback" style="display: none;">
                        Please provide Crop Name
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="soil">Soil</label>
                    <textarea rows="4" class="form-control @error('soil') is-invalid @enderror" id="soil" name="soil"
                        placeholder="Soil" value="{{ old('soil') }}"></textarea>
                    <div class="invalid-feedback soil-feedback" style="display: none;">
                        Please provide Soil
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="soil_as">Soil (Assamese)</label>
                    <textarea rows="4" class="form-control @error('soil_as') is-invalid @enderror" id="soil_as" name="soil_as"
                        placeholder="Soil in Assamese" value="{{ old('soil_as') }}"></textarea>
                    @error('soil_as')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="sowing_time">Sowing Time</label>
                    <textarea rows="4" class="form-control @error('sowing_time') is-invalid @enderror" id="sowing_time"
                        name="sowing_time" placeholder="Sowing Time" value="{{ old('sowing_time') }}"></textarea>
                    @error('sowing_time')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="sowing_time_as">Sowing Time (Assamese)</label>
                    <textarea rows="4" class="form-control @error('sowing_time_as') is-invalid @enderror" id="sowing_time_as"
                        name="sowing_time_as" placeholder="Sowing Time in Assamese" value="{{ old('sowing_time_as') }}"></textarea>
                    @error('sowing_time_as')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('admin.appmaster.suitability') }}" class="btn btn-warning">Cancel</a>
            </form>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        });


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
                            var sortedData = Object.entries(data).sort(function(a, b) {
                                return a[1].localeCompare(b[
                                    1]); // Compare crop names alphabetically
                            });

                            // Append sorted crop names to the dropdown
                            $.each(sortedData, function(index, [key, value]) {
                                cropNameSelect.append('<option value="' + key + '">' +
                                    value.toUpperCase() + '</option>');
                            });
                        }
                    });
                } else {
                    $('#crop_name_cd').empty().append('<option value="">Select Crop Name</option>');
                }
            });


            $('#cropSuitabilityForm').on('submit', function(e) {
            e.preventDefault();
            var isValid = true;

            var cropTypeCd = $('#crop_type_cd').val().trim();
            var cropNameCd = $('#crop_name_cd').val().trim();
            var soil = $('#soil').val().trim();
            var form = $(this);


            $('#crop_type_cd').removeClass('is-invalid');
            $('#crop_name_cd').removeClass('is-invalid');
            $('#soil').removeClass('is-invalid');
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

            if (soil === '') {
                $('#soil').addClass('is-invalid');
                $('.invalid-feedback.soil-feedback').show();
                isValid = false;
            }

            if (isValid) {
                form.off('submit').submit();
            }
        });

        });
    </script>
@endsection
