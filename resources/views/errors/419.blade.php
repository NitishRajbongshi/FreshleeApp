@extends('admin.common.layout')
@section('main')
<div class="py-2 text-center d-flex flex-column justify-content-center align-items-center" style="height: 57vh;">
    <h3>Session Expired!</h3>
    <p>Your session has expired. Please click the button below to log in again.</p>
    <a href="{{ route('auth.login') }}">Click here to login</a>
</div>
@endsection