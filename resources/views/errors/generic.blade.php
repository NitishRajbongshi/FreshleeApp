@extends('admin.common.layout')
@section('main')
<div class="py-2 text-center d-flex flex-column justify-content-center align-items-center" style="height: 57vh;">
    <h3>Oops! Something went wrong.</h3>
    <p>An unexpected error occurred. Please try again later.</p>
    <a href="{{ url('/') }}">Return to Homepage</a>
</div>
@endsection