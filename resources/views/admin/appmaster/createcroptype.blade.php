@extends('admin.common.layout')

@section('title', 'Create Crop Type')

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
            <h5 class="mb-0">Add Crop Type</h5>
        </div>
        <div class="card-body">
            <form id="cropTypeForm" action="{{ route('admin.appmaster.createcroptype') }}" method="POST"
                autocomplete="off">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="crop_type">Crop Type</label>
                    <input type="text" class="form-control" id="crop_type" name="crop_type" placeholder="Crop Type"
                        value="{{ old('crop_type') }}">
                    <div class="invalid-feedback crop_type-feedback" style="display: none;">
                        Please provide Crop Type
                    </div>
                </div>


                <div class="mb-3">
                    <label class="form-label" for="crop_type_assamese">Crop Type (Assamese)</label>
                    <input type="text" class="form-control @error('crop_type_assamese') is-invalid @enderror"
                        id="crop_type_assamese" name="crop_type_assamese" placeholder="Crop Type in Assamese"
                        value="{{ old('crop_type_assamese') }}">
                    @error('crop_type_assamese')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('admin.appmaster.croptype') }}" class="btn btn-warning">Cancel</a>
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


        $('#cropTypeForm').on('submit', function(e) {
            e.preventDefault();
            var isValid = true;

            var cropType = $('#crop_type').val().trim();
            var form = $(this);


            $('#crop_type').removeClass('is-invalid');
            $('.invalid-feedback').hide();


            if (cropType === '') {
                $('#crop_type').addClass('is-invalid');
                $('.invalid-feedback.crop_type-feedback').show();
                isValid = false;
            }

            if (isValid) {
                form.off('submit').submit();
            }
        });
    </script>
@endsection
