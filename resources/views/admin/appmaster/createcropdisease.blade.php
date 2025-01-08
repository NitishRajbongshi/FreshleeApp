@extends('admin.common.layout')

@section('title', 'Create Disease')

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
            <h5 class="mb-0">Add Disease</h5>
        </div>
        <div class="card-body">
            <form id="cropDiseaseForm" action="{{ route('admin.appmaster.createcropdisease') }}" method="POST"
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
                        Please provide Crop Type
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="disease_name">Disease Name</label>
                    <input type="text" class="form-control" id="disease_name" name="disease_name"
                        placeholder="Disease Name" value="{{ old('disease_name') }}">
                    <div class="invalid-feedback disease_name-feedback" style="display: none;">
                        Please provide Disease Name
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="disease_name_as">Disease Name (Assamese)</label>
                    <input type="text" class="form-control @error('disease_name_as') is-invalid @enderror"
                        id="disease_name_as" name="disease_name_as" placeholder="Disease Name in Assamese"
                        value="{{ old('disease_name_as') }}">
                    @error('disease_name_as')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="scientific_name">Scientific Name</label>
                    <input type="text" class="form-control @error('scientific_name') is-invalid @enderror"
                        id="scientific_name" name="scientific_name" placeholder="Scientific Name"
                        value="{{ old('scientific_name') }}">
                    @error('scientific_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="folder_name">Folder Name</label>
                    <input type="text" class="form-control @error('folder_name') is-invalid @enderror" id="folder_name"
                        name="folder_name" placeholder="Folder Name" value="{{ old('folder_name') }}">
                    @error('folder_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>


                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('admin.appmaster.cropdisease') }}" class="btn btn-warning">Cancel</a>
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


        $('#cropDiseaseForm').on('submit', function(e) {
            e.preventDefault();
            var isValid = true;

            var cropTypeCd = $('#crop_type_cd').val().trim();
            var diseaseName = $('#disease_name').val().trim();
            var form = $(this);


            $('#crop_type_cd').removeClass('is-invalid');
            $('#disease_name').removeClass('is-invalid');
            $('.invalid-feedback').hide();


            if (cropTypeCd === '') {
                $('#crop_type_cd').addClass('is-invalid');
                $('.invalid-feedback.crop_type_cd-feedback').show();
                isValid = false;
            }

            if (diseaseName === '') {
                $('#disease_name').addClass('is-invalid');
                $('.invalid-feedback.disease_name-feedback').show();
                isValid = false;
            }

            if (isValid) {
                form.off('submit').submit();
            }
        });

    </script>
@endsection
