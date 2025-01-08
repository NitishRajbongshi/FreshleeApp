@extends('admin.common.layout')

@section('title', 'Create Crop')

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
            <h5 class="mb-0">Add Crop</h5>
        </div>
        <div class="card-body">
            <form id="cropNameForm" action="{{ route('admin.appmaster.createcrop') }}" method="POST" autocomplete="off">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="crop_type_cd">Crop Type</label>
                    <select class="form-select" id="crop_type_cd" name="crop_type_cd">
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
                    <label class="form-label" for="crop_name">Crop Name</label>
                    <input type="text" class="form-control" id="crop_name" name="crop_name" placeholder="Crop Name"
                        value="{{ old('crop_name') }}">
                    <div class="invalid-feedback crop_name-feedback" style="display: none;">
                        Please provide Crop Name
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_name_assamese">Crop Name (Assamese)</label>
                    <input type="text" class="form-control @error('crop_name_assamese') is-invalid @enderror"
                        id="crop_name_assamese" name="crop_name_assamese" placeholder="Crop Name in Assamese"
                        value="{{ old('crop_name_assamese') }}">
                    @error('crop_name_assamese')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="crop_registry_no">Crop Registry Number</label>
                    <input type="text" class="form-control" id="crop_registry_no" name="crop_registry_no"
                        placeholder="Crop Registry Number" value="{{ old('crop_registry_no') }}">
                    <div class="invalid-feedback crop_registry_no-feedback" style="display: none;">
                        Please provide Crop Registry Number
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="scientific_name">Scientific Name</label>
                    <input type="text" class="form-control" id="scientific_name" name="scientific_name"
                        placeholder="Scientific Name" value="{{ old('scientific_name') }}">
                    <div class="invalid-feedback scientific_name-feedback" style="display: none;">
                        Please provide Scientific Name
                    </div>
                </div>




                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('admin.appmaster.cropinformation') }}" class="btn btn-warning">Cancel</a>
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


        $('#cropNameForm').on('submit', function(e) {
            e.preventDefault();
            var isValid = true;

            var cropTypeCd = $('#crop_type_cd').val().trim();
            var cropName = $('#crop_name').val().trim();
            var cropRegistry = $('#crop_registry_no').val().trim();
            var scientificName = $('#scientific_name').val().trim();
            var form = $(this);


            $('#crop_type_cd').removeClass('is-invalid');
            $('#crop_name').removeClass('is-invalid');
            $('#crop_registry_no').removeClass('is-invalid');
            $('#scientific_name').removeClass('is-invalid');
            $('.invalid-feedback').hide();


            if (cropTypeCd === '') {
                $('#crop_type_cd').addClass('is-invalid');
                $('.invalid-feedback.crop_type_cd-feedback').show();
                isValid = false;
            }

            if (cropName === '') {
                $('#crop_name').addClass('is-invalid');
                $('.invalid-feedback.crop_name-feedback').show();
                isValid = false;
            }

            if (cropRegistry === '') {
                $('#crop_registry_no').addClass('is-invalid');
                $('.invalid-feedback.crop_registry_no-feedback').show();
                isValid = false;
            }

            if (scientificName === '') {
                $('#scientific_name').addClass('is-invalid');
                $('.invalid-feedback.scientific_name-feedback').show();
                isValid = false;
            }

            if (isValid) {
                form.off('submit').submit();
            }
        });
    </script>
@endsection
