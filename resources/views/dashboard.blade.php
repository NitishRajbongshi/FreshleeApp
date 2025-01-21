@extends('admin.common.layout')
@section('title', 'Crop Name Management')
@section('custom_header')
@endsection
@section('main')
    @if ($message = Session::get('success'))
        <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div id="successAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card">
        <div class="">
            <h5 class="card-header">
                Hello {{$userName}}, <span class="text-sm">Welcome to Freshlee</span> 
            </h5>
        </div>
    @endsection
    @section('custom_js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
        </script>
    @endsection
