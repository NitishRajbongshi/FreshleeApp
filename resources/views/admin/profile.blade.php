@extends('admin.common.layout')

@section('title', 'Dashboard')

@section('custom_header')
@endsection

@section('main')
<h1>My Profile</h1>

<div class="card p-4">
    <form action="#" id="changePaswwordForm">
        @csrf
        <div class="mb-3">
            <label for="newPassword">New Password</label>
            <input type="password" name="newPassword" id="newPassword" class="form-control">
        </div>

        <div class="mb-3">
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" name="confirmPassword" id="confirmPassword" class="form-control">
        </div>

        <button type="submit" id="changePaswwordBtn" class="btn btn-success">Update</button>
    </form>
</div>
@endsection

@section('custom_js')
<script>

$(document).ready(function(){

const allElements = document.querySelectorAll('*');
                allElements.forEach(el => {
                    el.style.fontSize = '14px';
                });
            });

    $('#changePaswwordForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#changePaswwordBtn');
        btn.text('Please wait...');

        const formData = new FormData(this);

        $.ajax({
            url: "{{ route('auth.update.password') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.status == 0) {
                    alert(data.message);
                    btn.text('Update');
                } else if (data.status == 1) {
                    alert(data.message);
                    btn.text('Update');
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                alert('Something went wrong');
                btn.text('Update');
            }
        });
    });
</script>
@endsection
