@extends('admin.common.layout')
@section('title', "File Manager")

@section('custom_header') 
<link href="{{asset('vendor/lightbox/css/lightbox.min.css')}}" rel="stylesheet" />   
@endsection

@section('main')
    <div class="col-lg-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('admin.filemanager.upload') }}" class="btn btn-success">Upload images</a>
            </div>
        </div>
    </div>


    <div class="col-12 grid-margin">
        <div class="row mt-3">
            @forelse ($images as $image)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <a target="blank" href="{{ asset($image) }}"
                                class="d-flex justify-content-center align-items-center"
                                data-lightbox="my-gallery" data-title="image">
                                <img src="{{ asset($image) }}" alt="" class="img-fluid"
                                    style="height: 200px; object-fit:cover">
                            </a>
                            <button
                                class="mx-auto mt-2 btn deleteBtn btn-gradient-primary btn-rounded btn-icon d-flex justify-content-center align-items-center"
                                data-id="{{ Crypt::encrypt($image) }}" data-toggle="tooltip" data-placement="bottom"
                                title="Delete">
                                <i class="mdi mdi-delete-forever"></i>
                            </button>
                        </div>
                    </div>
                </div>
                {{-- <p>{{ $image }}</p> --}}
            @empty 
                <p>No image to display</p>
            @endforelse
        </div>
    </div>
@endsection


@section('custom_js') 
<script src="{{asset('vendor/lightbox/js/lightbox.min.js')}}"></script>
<script>
    lightbox.option({
      'resizeDuration': 200,
      'wrapAround': true
    })
</script>
@endsection

