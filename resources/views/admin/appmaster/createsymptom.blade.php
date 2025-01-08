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
            <h5 class="mb-0">Add Symptom</h5>
        </div>
        <div class="card-body">
            <form id="cropSymptomForm" action="{{ route('admin.appmaster.createsymptom') }}" method="POST"
                autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="language_cd">Language</label>
                    <select class="form-select @error('language_cd') is-invalid @enderror" id="language_cd"
                        name="language_cd">
                        <option value="">Select Language</option>
                        @foreach ($languages as $id => $desc)
                            <option value="{{ $id }}" {{ old('language_cd') == $id ? 'selected' : '' }}>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback language_cd-feedback" style="display: none;">
                        Please provide Language
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="disease_cd">Disease Name</label>
                    <select class="form-select @error('disease_cd') is-invalid @enderror" id="disease_cd" name="disease_cd">
                        <option value="">Select Disease Name</option>
                        @foreach ($disease as $id => $desc)
                            <option value="{{ $id }}" {{ old('disease_cd') == $id ? 'selected' : '' }}>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback disease_cd-feedback" style="display: none;">
                        Please provide Disease Name
                    </div>
                </div>


                <div class="mb-3">
                    <label class="form-label" for="symptom">Symptom</label>
                    <textarea rows="4" class="form-control" id="symptom" name="symptom"
                        placeholder="Symptom" value="{{ old('symptom') }}"></textarea>
                    <div class="invalid-feedback symptom-feedback" style="display: none;">
                        Please provide Symptom
                    </div>
                </div>


                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('admin.appmaster.symptom') }}" class="btn btn-warning">Cancel</a>
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


        $('#cropSymptomForm').on('submit', function(e) {
            e.preventDefault();
            var isValid = true;

            var langCd = $('#language_cd').val().trim();
            var diseaseName = $('#disease_cd').val().trim();
            var symptom = $('#symptom').val().trim();
            var form = $(this);


            $('#language_cd').removeClass('is-invalid');
            $('#disease_cd').removeClass('is-invalid');
            $('#symptom').removeClass('is-invalid');
            $('.invalid-feedback').hide();


            if (langCd === '') {
                $('#language_cd').addClass('is-invalid');
                $('.invalid-feedback.language_cd-feedback').show();
                isValid = false;
            }

            if (diseaseName === '') {
                $('#disease_cd').addClass('is-invalid');
                $('.invalid-feedback.disease_cd-feedback').show();
                isValid = false;
            }

            if (symptom === '') {
                $('#symptom').addClass('is-invalid');
                $('.invalid-feedback.symptom-feedback').show();
                isValid = false;
            }

            if (isValid) {
                form.off('submit').submit();
            }
        });
    </script>
@endsection
