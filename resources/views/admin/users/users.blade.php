<?php
use App\Common\Activation;
?>

@extends('admin.common.layout')

@section('title', 'Users')

@section('custom_header')
@endsection

@section('main')

@if ($message = Session::get('success'))
  <div class="alert alert-success alert-dismissible" role="alert">
    {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif


<div class="card">
    <div class="d-flex align-items-center">
        <h5 class="card-header">Users</h5>
        <div>
            <a href="{{route('admin.users.create')}}" class="btn btn-outline-info">
              <i class="tf-icons bx bx-plus-medical"></i>Create New User
            </a>
        </div>
    </div>

    <div class="table-responsive text-nowrap px-4">
      <table class="table" id="tblUser">
        <thead>
          <tr>
            <th>Sl. No.</th>
            <th>Name</th>
            <th>Office</th>
            <th>Designation</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">

       @forelse ($users as $index => $item)
            <tr>
                <td>{{$index + 1}}</td>
                <td>{{$item->name}}</td>
                <td>{{$item->office->name}}, <br>{{$item->office->address}}</td>
                <td>{{$item->designation->designation_name}},<br>
                   {{$item->department->department_name}}</td>
                <td>{{$item->email}}</td>
                <td>
                   @if ($item->status == Activation::Activate)
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="checkStatus" data-id="{{ Crypt::encrypt($item->id) }}" checked>
                        <label class="form-check-label" for="checkStatus" ></label>
                      </div>
                    @else
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="checkStatus" data-id="{{ Crypt::encrypt($item->id) }}">
                        <label class="form-check-label" for="checkStatus"></label>
                      </div>
                    @endif
                </td>
                <td>
                    <a href="{{route('admin.users.edit', ['id' => Crypt::encrypt($item->id)])}}"
                      class="btn btn-sm btn-outline-primary">
                      <i class="tf-icons bx bx-edit"></i>Edit</a>

                    {{-- <button class="btn btn-sm btn-outline-danger deleteUserBtn"
                    data-id="{{Crypt::encrypt($item->id)}}">
                    <i class="tf-icons bx bx-trash"></i>Delete</button> --}}

                </td>
            </tr>
        @empty
            <tr><td>No users found.</td></tr>
        @endforelse

        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('custom_js')

<script>
  $(document).ready( function () {
    const allElements = document.querySelectorAll('*');
                allElements.forEach(el => {
                    el.style.fontSize = '14px';
                });
    $('#tblUser').DataTable();

    // Change status
    $(document.body).on('change', '#checkStatus', function () {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var id = $(this).data('id');
        var formDat = {
            id: id,
            active: status
        }
        // console.log(formDat);
        $.ajax({
            type: "post",
            url: "{{ route('admin.user.changestatus') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formDat,
            success: function (data) {
                if(data.status == 2 ) {
                  $(this).prop('checked', true);
                  alert(data.message);
                  console.log(data);
                }
                else
                {
                  console.log(data);
                }
            }
        });
    });

  } );
</script>

@endsection
