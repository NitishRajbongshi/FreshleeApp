@extends('admin.common.layout')
@section('main')
<div class="py-2 text-center d-flex flex-column justify-content-center align-items-center" style="height: 57vh;">
    <h3>Validation Error !!!</h3>
    <p>Please Check Your Password, Captcha Entered Correctly!!!!.</p>
    <a href="{{ url('login') }}">Return to Login</a>
</div>
@endsection