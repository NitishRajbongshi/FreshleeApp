@extends('admin.common.layout')

@section('title', 'Create user')

@section('custom_header')
@endsection

@section('main')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Create user</h5>
    </div>
    <div class="card-body">
      <form action="{{route('admin.users.create')}}" method="POST">
        @csrf
        <div class="mb-3">
          <input type="hidden" id="password" name="password"
          value="{{Hash::make( env('NEW_USER_DEFAULT_PASSWORD') )}}" />
          <label class="form-label" for="name">Full Name</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="John Doe">

          @error('name')
            <div class="invalid-feedback">
              {{$message}}
            </div>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label" for="office_id">Office</label>
          <select class="form-select  @error('office_id') is-invalid @enderror"
          id="office_id" name="office_id" aria-label="Default select example">
            <option selected disabled value="">Select Office</option>
            @forelse ($offices as $item)
            <option value="{{$item->id}}">{{$item->name}}, {{$item->address}}</option>
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

        <div class="mb-3">
          <label class="form-label" for="department_id">Department</label>
          <select class="form-select  @error('department_id') is-invalid @enderror"
          id="department_id" name="department_id" aria-label="Default select example">
            <option selected disabled value="">Select Department</option>
            @forelse ($departments as $item)
            <option value="{{$item->id}}">{{$item->department_name}}</option>
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
        <div class="mb-3">
          <label class="form-label" for="designation_id">Designation</label>
          <select class="form-select  @error('designation_id') is-invalid @enderror"
          id="designation_id" name="designation_id" aria-label="Default select example">
            <option selected disabled value="">Select Desegnation</option>
            @forelse ($designations as $item)
            <option value="{{$item->id}}">{{$item->designation_name}}</option>
            @empty
                <option disabled value="">No data found</option>
            @endforelse
          </select>
          @error('designation_id')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
          @enderror
        </div>
        <div class="mb-3">
          <label class="form-label" for="email">Email</label>
          <div class="input-group input-group-merge">
            <input type="text" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
            placeholder="john.doe" aria-label="john.doe" aria-describedby="basic-default-email2">
            @error('email')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label" for="phone">Phone No</label>
          <input type="text" id="phone" name="phone"
          class="form-control phone-mask  @error('phone') is-invalid @enderror">
          @error('phone')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
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
