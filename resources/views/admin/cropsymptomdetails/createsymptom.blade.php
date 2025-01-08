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
            <h5 class="mb-0">Add Symptom</h5>
        </div>
        <div class="card-body">
            <form id="cropSymptomForm" action="{{ route('admin.cropsymptomdetails.createsymptom') }}" method="POST"
                autocomplete="off">
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
                        Please provide Crop Type
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_name_cd">Crop Name</label>
                    <select class="form-select @error('crop_name_cd') is-invalid @enderror" id="crop_name_cd"
                        name="crop_name_cd">
                        <option value="">Select Crop Name</option>
                    </select>
                    <div class="invalid-feedback crop_name_cd-feedback" style="display: none;">
                        Please provide Crop Name
                    </div>
                </div>

                <div class="mb-3">
                    <label for="disease_cd" class="form-label">Disease</label>
                    <select class="form-select @error('disease_cd') is-invalid @enderror" id="disease_cd" name="disease_cd">
                        <option value="">Select Disease</option>
                    </select>
                    <div class="invalid-feedback disease_cd-feedback" style="display: none;">
                        Please provide Disease
                    </div>
                </div>

                <!-- New Language Dropdown -->
                <div class="mb-3">
                    <label for="language_cd" class="form-label">Language</label>
                    <select class="form-select @error('language_cd') is-invalid @enderror" id="language_cd"
                        name="language_cd">
                        <option value="">Select Language</option>
                        <option value="as" {{ old('language_cd') == 'as' ? 'selected' : '' }}>ASSAMESE</option>
                        <option value="en" {{ old('language_cd') == 'en' ? 'selected' : '' }}>ENGLISH</option>
                    </select>
                    <div class="invalid-feedback language_cd-feedback" style="display: none;">
                        Please provide Language
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="symptom">Symptom</label>
                    <textarea rows="4" class="form-control @error('symptom') is-invalid @enderror" id="symptom" name="symptom"
                        placeholder="Symptom" value="{{ old('symptom') }}"></textarea>
                    <div class="invalid-feedback symptom-feedback" style="display: none;">
                        Please provide Symptom
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('admin.cropsymptomdetails') }}" class="btn btn-warning">Cancel</a>
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
                            cropNameSelect.empty();
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

            // Fetch diseases based on crop name selection
            $('#crop_name_cd').change(function() {
                var cropNameCd = $(this).val();
                if (cropNameCd) {
                    $.ajax({
                        url: '{{ route('admin.cropsymptomdetails.getDisease') }}',
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


            $('#cropSymptomForm').on('submit', function(e) {
                e.preventDefault();
                var isValid = true;

                var cropTypeCd = $('#crop_type_cd').val().trim();
                var cropNameCd = $('#crop_name_cd').val().trim();
                var diseaseCd = $('#disease_cd').val().trim();
                var languageCd = $('#language_cd').val().trim();
                var symp = $('#symptom').val().trim();
                var form = $(this);


                $('#crop_type_cd').removeClass('is-invalid');
                $('#crop_name_cd').removeClass('is-invalid');
                $('#disease_cd').removeClass('is-invalid');
                $('#language_cd').removeClass('is-invalid');
                $('#symptom').removeClass('is-invalid');
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

                if (languageCd === '') {
                    $('#language_cd').addClass('is-invalid');
                    $('.invalid-feedback.language_cd-feedback').show();
                    isValid = false;
                }

                if (symp === '') {
                    $('#symptom').addClass('is-invalid');
                    $('.invalid-feedback.symptom-feedback').show();
                    isValid = false;
                }

                if (isValid) {
                    form.off('submit').submit();
                }
            });

        });
    </script>
@endsection
