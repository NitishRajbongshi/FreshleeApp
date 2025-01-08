@extends('admin.common.layout')
@section('main')
<div class="py-2 text-center d-flex flex-column justify-content-center align-items-center" style="height: 57vh;">
    <h3>404 - Page Not Found</h3>
    <p>Sorry, the page you are looking for could not be found.</p>
    <a href="{{ url('/') }}">Return to Homepage</a>
</div>
@endsection