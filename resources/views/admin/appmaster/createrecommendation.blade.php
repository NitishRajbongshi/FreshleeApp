@extends('admin.common.layout')

@section('title', 'Create Recommendation')

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
            <h5 class="mb-0">Add Recommendation</h5>
        </div>
        <div class="card-body">
            <form id="croprecomForm" action="{{ route('admin.appmaster.createrecommendation') }}" method="POST" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="crop_name_cd">Crop Name</label>
                    <select class="form-select @error('crop_name_cd') is-invalid @enderror" id="crop_name_cd"
                        name="crop_name_cd">
                        <option value="">Select Crop</option>
                        @foreach ($crops as $id => $desc)
                            <option value="{{ $id }}" {{ old('crop_name_cd') == $id ? 'selected' : '' }}>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback crop_name_cd-feedback" style="display: none;">
                        Please provide Crop Name
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
                    <label class="form-label" for="control_measure">Control Measure</label>
                    <textarea rows="4" class="form-control @error('control_measure') is-invalid @enderror" id="control_measure"
                        name="control_measure" placeholder="Control Measure" value="{{ old('control_measure') }}"></textarea>
                        <div class="invalid-feedback control_measure-feedback" style="display: none;">
                            Please provide Control Measure
                        </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="control_measure_as">Control Measure (Assamese)</label>
                    <textarea rows="4" class="form-control @error('control_measure_as') is-invalid @enderror" id="control_measure_as"
                        name="control_measure_as" placeholder="Control Measure in Assamese" value="{{ old('control_measure_as') }}"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('admin.appmaster.recommendation') }}" class="btn btn-warning">Cancel</a>
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


    $('#croprecomForm').on('submit', function(e) {
        e.preventDefault();
        var isValid = true;

        var cropNameCd = $('#crop_name_cd').val().trim();
        var diseaseName = $('#disease_cd').val().trim();
        var controlMeasure = $('#control_measure').val().trim();
        var form = $(this);


        $('#crop_name_cd').removeClass('is-invalid');
        $('#disease_cd').removeClass('is-invalid');
        $('#control_measure').removeClass('is-invalid');
        $('.invalid-feedback').hide();


        if (cropNameCd === '') {
            $('#crop_name_cd').addClass('is-invalid');
            $('.invalid-feedback.crop_name_cd-feedback').show();
            isValid = false;
        }

        if (diseaseName === '') {
            $('#disease_cd').addClass('is-invalid');
            $('.invalid-feedback.disease_cd-feedback').show();
            isValid = false;
        }

        if (controlMeasure === '') {
            $('#control_measure').addClass('is-invalid');
            $('.invalid-feedback.control_measure-feedback').show();
            isValid = false;
        }

        if (isValid) {
            form.off('submit').submit();
        }
    });
</script>
@endsection
