@extends('admin.common.layout')

@section('title', 'Edit user')

@section('custom_header')
@endsection

@section('main')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Edit User Profile</h5>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('admin.users.edit_save', Crypt::encrypt($user->id) )}}">
        @csrf
        {{-- Name --}}
        <div class="mb-3">
          <label class="form-label" for="name">Full Name</label>
          <input type="text" class="form-control  @error('name') is-invalid @enderror"
          id="name" name="name" value="{{ $user->name }}"  placeholder="John Doe">

          @error('name')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Office --}}
        <div class="mb-3">
          <label class="form-label" for="office_id">Office</label>
          <select class="form-select  @error('office_id') is-invalid @enderror"
          id="office_id" name="office_id" aria-label="Default select example">
            <option selected disabled value="">Select Office</option>
            @forelse ($offices as $item)
            @if ($user->office_id == $item->id)
              <option value="{{$item->id}}" selected>{{$item->name}}, {{$item->address}}</option>
            @else
              <option value="{{$item->id}}">{{$item->name}}, {{$item->address}}</option>
            @endif
            @empty
                <option disabled value="">No data found</option>
            @endforelse
          </select>

          @error('office_id')
            <div class="invalid-feedback">
              {{$message}}
            </div>
          @enderror
        </div>

        {{-- Department --}}
        <div class="mb-3">
          <label class="form-label" for="department_id">Department</label>
          <select class="form-select  @error('department_id') is-invalid @enderror" id="department_id" name="department_id" aria-label="Default select example">
            <option selected disabled value="">Select</option>
            @forelse ($departments as $item)
              @if ($user->department_id == $item->id)
                  <option value="{{$item->id}}" selected>{{$item->department_name}}</option>
              @else
                  <option value="{{$item->id}}">{{$item->department_name}}</option>
              @endif

            @empty
                <option disabled value="">No data found</option>
            @endforelse
          </select>

          @error('department_id')
            <div class="invalid-feedback">
              {{$message}}
            </div>
          @enderror
        </div>

        {{-- Designation --}}
        <div class="mb-3">
          <label class="form-label" for="designation_id">Designation</label>
          <select class="form-select  @error('designation_id') is-invalid @enderror" id="designation" name="designation_id" aria-label="Default select example">
            <option selected disabled value="">Select</option>
            @forelse ($designations as $item)
              @if ($user->designation_id == $item->id)
                <option value="{{$item->id}}" selected >{{$item->designation_name}}</option>
              @else
                <option value="{{$item->id}}">{{$item->designation_name}}</option>
              @endif

            @empty
                <option disabled value="">No data found</option>
            @endforelse
          </select>

          @error('designation_id')
            <div class="invalid-feedback">
              {{$message}}
            </div>
          @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
          <label class="form-label" for="email">Email
            <span class="badge bg-label-warning">Read Only</span></label>
          <div class="input-group input-group-merge">
            <input type="text" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ $user->email }}" readonly placeholder="john.doe" aria-label="john.doe" aria-describedby="basic-default-email2">

            @error('email')
              <div class="invalid-feedback">
                {{$message}}
              </div>
            @enderror
          </div>
        </div>

        {{-- Phone No --}}
        <div class="mb-3">
          <label class="form-label" for="phone">Phone No</label>
          <input type="text" id="phone" name="phone"
          class="form-control phone-mask  @error('phone') is-invalid @enderror"
          value="{{ $user->phone }}">

          @error('phone')
            <div class="invalid-feedback">
              {{$message}}
            </div>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.users') }}"  class="btn btn-warning">Cancel</a>

      </form>
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
    } );
</script>
@endsection
