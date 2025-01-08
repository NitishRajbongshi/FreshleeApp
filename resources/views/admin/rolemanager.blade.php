@extends('admin.common.layout')

@section('title', 'Role Manager')

@section('custom_header')
@endsection

@section('main')
<h1>Role Manager</h1>

<div class="col-md-12">
    <div class="card mb-4">
      <h5 class="card-header">Form Controls</h5>
      <div class="card-body">

        <form action="{{route('admin.role.set')}}" method="post">
          @csrf
          <div class="mb-3">
            <label for="user" class="form-label">Select User</label>
            <select class="form-select" id="user" name="user" required>
              <option selected="">Select user</option>
              @forelse ($user as $item)
                @if(!empty($item->name))
                  <option value="{{$item->user_id}}" class="text-capitalize">{{$item->name}}</option>
                @endif
              @empty
                  <option>No data found</option>
              @endforelse
            </select>
          </div>

          <div class="mb-3">
              <label for="role" class="form-label">Select Role</label>
              <select class="form-select" id="role" name="role" required>
                <option selected="">Open this select menu</option>
                @forelse ($role as $item)
                <option value="{{$item->role_title}}" class="text-capitalize">{{$item->role_desc}}</option>
              @empty
                  <option>No data found</option>
              @endforelse

              </select>
            </div>

            <button type="submit" class="btn btn btn-primary">Submit</button>
        </form>
      </div>
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
      $('#tblDesignation').DataTable();
    } );
  </script>
@endsection
