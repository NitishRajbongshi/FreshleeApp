@extends('admin.common.layout')



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
        <h5 class="card-header">Agri News Manager</h5>
        <div class="float-begin col-md-3">
            <a href="{{route('admin.agrinews.create')}}" class="btn btn-outline-info">
              <i class="tf-icons bx bx-plus-medical"></i>Create New News
            </a>
        </div>

        <div class="float-end">
            <a type="button" class="btn btn-outline-secondary" href="{{route('agrinews.categorymanager')}}">
              <i class="tf-icons bx bx-cog"></i>
                News Category Manager
            </a>
        </div>

    </div>


  <div class="table-responsive text-nowrap px-4">
    <table class="table" id="tblNews">
      <thead>
        <tr>
          <th>Sl. No.</th>
          <th>Category</th>
          <th>News</th>
          <th>Created By</th>
          <th>Has Attach.</th>
          <th>Published</th>
          {{-- <th>Actions</th> --}}
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
    @forelse ($news as $index => $item)
          <tr>
              <td>{{$index + 1}}</td>
              <td>{{$item->catg_descr}}</td>
              <td>
                <div class='text-wrap'>
                  <strong>{{$item->news_title}}</strong>
                  <br>
                  {{$item->news_descr}}
                </div>
              </td>
              <td>{{$item->news_uploaded_by}}</td>
              <td>{{($item->has_attachment)?"Yes":"No"}}</td>
              <td>{{($item->ispublished)?'Yes': 'No'}}</td>
              {{-- <td> --}}
                  {{-- <a href="{{route('admin.users.edit', ['id' => Crypt::encrypt($item->id)])}}"  --}}
                    {{-- <a href="#"
                    class="btn btn-sm btn-outline-primary">
                    <i class="tf-icons bx bx-edit"></i>Edit</a> --}}

                  {{-- <button class="btn btn-sm btn-outline-danger deleteUserBtn"
                  data-id="{{Crypt::encrypt($item->id)}}">
                  <i class="tf-icons bx bx-trash"></i>Delete</button> --}}

              {{-- </td> --}}
          </tr>
      @empty
          <tr><td>No users found.</td></tr>
      @endforelse

      </tbody>
    </table>
  </div>
</div>



@endsection {{-- End of main --}}

@section('custom_js')
<script>
    $(document).ready( function () {
    const allElements = document.querySelectorAll('*');
                allElements.forEach(el => {
                    el.style.fontSize = '14px';
                });
      $('#tblNews').DataTable();
    } );
</script>
@endsection
