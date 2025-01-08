@extends('admin.common.layout')

@section('title', 'Create Variety')

@section('custom_header')
@endsection

@section('main')
    @if ($message = Session::get('success'))
        <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4" id="editModal">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add Variety</h5>
        </div>
        <div class="card-body">
            <form id="cropVarietyForm" action="{{ route('admin.cropvarietydetails.createvariety') }}" method="POST"
                autocomplete="off">
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
                        Please select a Crop Type.
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
                        Please select a Crop Name.
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_variety_desc">Crop Variety Name</label>
                    <input type="text" class="form-control" id="crop_variety_desc" name="crop_variety_desc"
                        placeholder="Crop Variey" value="{{ old('crop_variety_desc') }}">
                    <div class="invalid-feedback crop_variety_desc-feedback" style="display: none;">
                        Please provide Crop variety Name
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_variety_desc_ass">Crop Variety Name (Assamese)</label>
                    <input type="text" class="form-control" id="crop_variety_desc_as" name="crop_variety_desc_as"
                        placeholder="Crop Variety in Assamese" value="{{ old('crop_variety_desc_as') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_variety_dtls">Crop Variety Details</label>
                    <textarea rows="4" class="form-control" id="crop_variety_dtls" name="crop_variety_dtls"
                        placeholder="Crop Variety Details" value="{{ old('crop_variety_dtls') }}"></textarea>
                    <div class="invalid-feedback crop_variety_dtls-feedback" style="display: none;">
                        Please provide Crop variety Details
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_variety_dtls_as">Crop Variety Details (Assamese)</label>
                    <textarea rows="4" class="form-control" id="crop_variety_dtls_as" name="crop_variety_dtls_as"
                        placeholder="Crop Variety Details in Assamese" value="{{ old('crop_variety_dtls_as') }}"></textarea>
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
                            cropNameSelect.append(
                                '<option value="">Select Crop Name</option>');
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
                    $('#crop_name_cd').empty().append(
                        '<option value="">Select Crop Name</option>');
                }
            });


            $('#cropVarietyForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                var isValid = true;


                var cropTypeCd = $('#crop_type_cd').val().trim();
                var cropNameCd = $('#crop_name_cd').val().trim();
                var cropVariety = $('#crop_variety_desc').val().trim();
                var cropVarietyDetails = $('#crop_variety_dtls').val().trim();
                var form = $(this);


                $('#crop_type_cd').removeClass('is-invalid');
                $('#crop_name_cd').removeClass('is-invalid');
                $('#crop_variety_desc').removeClass('is-invalid');
                $('#crop_variety_dtls').removeClass('is-invalid');
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

                if (cropVariety === '') {
                    $('#crop_variety_desc').addClass('is-invalid');
                    $('.invalid-feedback.crop_variety_desc-feedback').show();
                    isValid = false;
                }

                if (cropVarietyDetails === '') {
                    $('#crop_variety_dtls').addClass('is-invalid');
                    $('.invalid-feedback.crop_variety_dtls-feedback').show();
                    isValid = false;
                }

                if (isValid) {
                    form.off('submit').submit();
                }

            });
        });
    </script>
@endsection
