@extends('admin.common.layout')
@section('main')
<div class="py-2 text-center d-flex flex-column justify-content-center align-items-center" style="height: 57vh;">
    <h3>405 - Method Not Allowed</h3>
    <p>Oops! The request method is not supported for this route. Please try again or go back to the homepage.</p>
    <a href="{{ url('/') }}">Return to Homepage</a>
</div>
@endsection