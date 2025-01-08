@extends('admin.common.layout')

@section('title', 'Agri News Category Manager')

@section('custom_header')
@endsection

@section('main')

<div class="card">
    <div class="d-flex align-items-center">
        <h5 class="card-header">Agri News Category Manager</h5>

        <div class="col-md-3">
            <button type="button" class="btn btn-outline-info openCreateModalBtn" data-bs-toggle="modal"
            data-bs-target="#createCategoryModal">
              <i class="tf-icons bx bx-plus-medical"></i>
                Create News Category
            </button>
        </div>

        <div>
            <a type="button" class="btn btn-outline-secondary" href="{{route('agrinews.newsmanager')}}">
                <i class='bx bxs-chevrons-left' ></i>
                Return to News Manager
            </a>
        </div>
    </div>

    <div class="table-responsive text-nowrap px-4">
        <table class="table" id="tblNewsCategrory">
          <thead>
            <tr>
              <th>Sl.No.</th>
              <th>Category Name</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">

              @forelse ($newsCategories as $index => $item)
              <tr>
                  <td>
                    <strong>{{$index + 1}}</strong>
                  </td>
                  <td>{{$item->catg_descr}}</td>
                  <td>
                    <button class="btn btn-sm btn-outline-primary openEditModalBtn"
                    data-id="{{Crypt::encrypt($item->catg_cd)}}"
                    data-category = "{{$item->catg_descr}}">
                    <i class='bx bx-edit'></i>Edit</button>
                  </td>
                </tr>
              @empty
                  <tr>
                      <td class="text-warning text-center" colspan="3">No data found</td>
                  </tr>
              @endforelse

          </tbody>
        </table>
      </div>
    </div>


    {{-- Create New Category --}}
    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="createCategoryModalTitle">Create News Category</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createCategoryForm" >
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="createCategoryName" class="form-label">New Category</label>
                            <input type="text" id="createCategoryName" name="createCategoryName" class="form-control" placeholder="Enter Name" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="createCategoryBtn" name="createCategoryBtn" class="btn btn-primary">Create</button>
                </div>
            </form>
          </div>
        </div>
    </div>


    {{-- Edit News Category Modal --}}
    <div class="modal fade" id="editNewsCategoryModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editNewsCategoryModalTitle">Edit News Category</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" id="editNewsCategoryForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                      <div class="col mb-3">
                        <input type="hidden" name="editCategoryCd" id="editCategoryCd" value="">
                        <label for="editCategoryName" class="form-label">News Category</label>
                        <input type="text" id="editCategoryName" name="editCategoryName" class="form-control" placeholder="Enter Name" required>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                      Close
                    </button>
                    <button type="submit" class="btn btn-primary" id="editNewsCategoryBtn">Update</button>
                  </div>
            </form>
          </div>
        </div>
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
        // Open Create Modal
        $('.openCreateModalBtn').on('click', function(){
            $('#createCategoryModal').on('shown.bs.modal', function (e) {
            $('#createCategoryName').focus();
            }).modal('show');
        });

        // Open Edit Modal
        $('.openEditModalBtn').on('click', function(){
            const catgCd = $(this).data('id');
            $('#editCategoryCd').val(catgCd);
            $('#editCategoryName').val( $(this).data('category'));

            $('#editNewsCategoryModal').on('shown.bs.modal', function (e) {
            $('#editCategoryName').focus();
            }).modal('show');
        });

        // Create News Category AJAX
        $('#createCategoryForm').on('submit', function(e){
            e.preventDefault();

            $.ajax ({
                url: '{{route('agrinews.categorymanager.create')}}',
                data: $('#createCategoryForm').serialize(),
                type: "POST",
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                success: function(data){
                    if(data.status == 1) {
                        alert(data.message);
                        $('#createCategoryModal').modal('hide');
                        location.reload();
                    }
                    else if(data.status == 0) {
                        alert(data.message);
                    }
                },
                error: function(){
                    alert("Creating category failed.Something went wrong!");
                }
            });
        });

        // Edit News Category AJAX
        $('#editNewsCategoryForm').on('submit', function(e){
            e.preventDefault();

            $.ajax ({
                url: '{{route('agrinews.categorymanager.edit')}}',
                data: $('#editNewsCategoryForm').serialize(),
                type: "POST",
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                success: function(data){
                    if(data.status == 1) {
                        alert(data.message);
                        $('#editNewsCategoryModal').modal('hide');
                        location.reload();
                    }
                    else if(data.status == 0) {
                        alert(data.message);
                    }
                },
                error: function(){
                    alert("Creating category failed.Something went wrong!");
                }

            });
        });

    $('#tblNewsCategrory').DataTable();
});
</script>
@endsection
