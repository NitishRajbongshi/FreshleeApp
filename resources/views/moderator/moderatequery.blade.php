@extends('moderator.common.layout')

@section('title', 'Moderate Queries')

@section('custom_header')
@endsection

@section('main')

<div class="card">
    <div class="d-flex align-items-center">
        <h5 class="card-header">Query Viewer</h5>
        <div>
            <button type="button" class="btn btn-outline-success" id="queryCategoryManagerBtn" data-bs-toggle="modal"
            data-bs-target="#manageQueryCategoryModal">
              <i class="tf-icons bx bx-plus-medical"></i>
                Manage Query Categories
            </button>
        </div>
    </div>

    <div class="table-responsive text-nowrap px-4">
        <table class="table" id="tblQueries">
          <thead>
            <tr>
              <th>No.</th>
              <th>ACK No.</th>
              <th>Query</th>
              {{-- <th>Category</th> --}}
              <th>District</th>
              <th>Attach.</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
              @forelse ($queries as $index => $item)
              <tr>
                  <td>
                    <strong>{{$index + 1}}</strong>
                  </td>
                  <td><div class='text-wrap'>{{ $item->ack_no }}</div></td>
                  <td><div class='text-wrap'>{{ $item->query_desc }}</div></td>
                  {{-- <td>{{ $item->queryCategory->catg_descr ?? 'None' }}</td> --}}
                  <td><div class="text-wrap">{{ $item->district }}</div></td>
                  <td>
                    @if ( $item->has_attachment )
                        {{ "Yes" }}
                    @else
                        {{ "No" }}
                    @endif
                  </td>
                  <td>
                    <button class="btn btn-sm btn-outline-primary OpenViewModalBtn"
                    data-query_id="{{ Crypt::encrypt($item->query_id) }}"
                    data-query_submitted_by = "{{ $item->query_submitted_by }}"
                    data-query_submitted_on = "{{ $item->query_submitted_on }}"
                    data-query_district = "{{ $item->district }}"
                    data-query_desc = "{{$item->query_desc}}"
                    data-ack_no = "{{ $item->ack_no }}"
                    data-district = "{{ $item->district }}"
                    data-toggle="tooltip" data-placement="top" title="View Query">
                    <i class='bx bx-show'></i></button>

                    <button class="btn btn-sm btn-outline-success OpenAcceptModalBtn"
                    data-query_id="{{ Crypt::encrypt($item->query_id) }}"
                    data-query_submitted_by = "{{ $item->query_submitted_by }}"
                    data-query_submitted_on = "{{ $item->query_submitted_on }}"
                    data-query_district = "{{ $item->district }}"
                    data-query_desc = "{{$item->query_desc}}"
                    data-ack_no = "{{ $item->ack_no }}"
                    data-district = "{{ $item->district }}""
                    data-toggle="tooltip" data-placement="top" title="Accept Query">
                    <i class='bx bx-check-circle'></i></button>

                    <button class="btn btn-sm btn-outline-danger OpenRejectModalBtn"
                    data-query_id="{{ Crypt::encrypt($item->query_id) }}"
                    data-query_submitted_by = "{{ $item->query_submitted_by }}"
                    data-query_submitted_on = "{{ $item->query_submitted_on }}"
                    data-query_district = "{{ $item->district }}"
                    data-query_desc = "{{$item->query_desc}}"
                    data-ack_no = "{{ $item->ack_no }}"
                    data-query_desc = "{{$item->query_desc}}"
                    data-toggle="tooltip" data-placement="top" title="Reject Query">
                    <i class='bx bx-x-circle'></i></button>
                  </td>
                </tr>
              @empty
                <tr>
                    <td colspan="3">No data found</td>
                </tr>
              @endforelse

          </tbody>
        </table>
      </div>
    </div>

{{-- Manage Query Category Modal --}}
<div class="modal fade" id="manageQueryCategoryModal" tabindex="-1" aria-hidden="true" style= "display: none;">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >View Question</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="formCreateCategory">
        @csrf
        <div class="modal-body" >

          <div class="row">
            <div class="col mb-3">
              <label for="category_name" class="form-label">Category Name</label>
              <input type="text" id="category_name" name="category_name" class="form-control" placeholder="Enter Category Name" required="">
            </div>
          </div>

        </div>


        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Close
          </button>
          <button type="submit" class="btn btn-primary" id="createCategoryBtn">Add</button>
        </div>
      </form>


    </div>
  </div>
</div>

    {{-- View Question Modal --}}
    <div class="modal fade" id="viewQuestionModal" tabindex="-1" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" >View Question</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">

            <div class="table-responsive text-wrap">
              <table class="table table-hover">
                <tbody class="table-border-bottom-0">
                  <tr><th>ACK No.: </th><td id="view_ack_no"></td></tr>
                  <tr><th>Submitted By: </th><td id='view_query_submitted_by'></td></tr>
                  <tr><th>Submitted On: </th><td id='view_query_submitted_on'></td></tr>
                  <tr><th>District: </th><td id='view_query_district'></td></tr>
                  <tr><th>Query:</th><td id="view_question_descr"></td></tr>
                  <tr><th>Attachments:</th>
                    <td></td>
                  </tr>
                </tbody>
              </table>

            </div>
          </div>
          <div class="modal-footer">

            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
              Close
            </button>
          </div>

        </div>
      </div>
    </div>

    {{-- Accept Question Modal --}}
    <div class="modal fade" id="acceptQuestionModal" tabindex="-1" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" >Accept Question</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">

            <div class="table-responsive text-wrap">
              <table class="table table-hover">
                <tbody class="table-border-bottom-0">
                  <tr><th>ACK No.: </th><td id="accept_ack_no"></td></tr>
                  <tr><th>Submitted By: </th><td id='accept_query_submitted_by'></td></tr>
                  <tr><th>Submitted On: </th><td id='accept_query_submitted_on'></td></tr>
                  <tr><th>District: </th><td id='accept_query_district'></td></tr>
                  <tr><th>Query:</th><td id="accept_question_descr"></td></tr>
                  <tr><th>Attachments:</th>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </div>

            <form id="formAcceptQuery">
              @csrf
                 <input type="hidden" id="accept_query_id" name="accept_query_id"  value="">
                 <div class="row">
                  <label for="queryCategorySelect" class="col-sm-3 col-form-label"><strong> Category: </strong></label>
                  <div class="col-sm-9">
                      <select class="form-select" id="queryCategorySelect" name="queryCategorySelect" aria-label="Select Query Category" required>
                        <option selected value="" >Select Category</option>
                        @foreach ($categories as $category)
                          <option value="{{ $category->catg_id }}">{{ $category->catg_descr }}</option>
                        @endforeach
                      </select>
                    </div>
                 </div>

                 <div class="row mt-2">
                  <label for="queryAnswer" class="col-sm-3 col-form-label"><strong> Write Answer: </strong></label>
                  <div class="col-sm-9">
                      <textarea rows="4" class="form-control" id="queryAnswer" name="queryAnswer" aria-label="Write an answer"></textarea>
                  </div>
                 </div>
          </div>
          {{-- End of Modal Body --}}

          <div class="modal-footer">

            <a href="#" class="btn btn-info" id="linkQueryButton">
              <i class='bx bx-link'></i>
              Link to a Parent Query
            </a>

            <button type="submit" class="btn btn-success" id="acceptQuerybtn" >
              <i class='bx bx-check-circle'></i>
              Accept
            </button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class='bx bx-x' ></i>
              Close
            </button>
          </div>
        </form>

        </div>
      </div>
    </div>

     {{-- Reject Question Modal --}}
     <div class="modal fade" id="rejectQuestionModal" tabindex="-1" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" >Reject Question</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">

            <div class="table-responsive text-wrap">
              <table class="table table-hover">
                <tbody class="table-border-bottom-0">
                  <tr><th>ACK No.: </th><td id="reject_ack_no"></td></tr>
                  <tr><th>Submitted By: </th><td id='reject_query_submitted_by'></td></tr>
                  <tr><th>Submitted On: </th><td id='reject_query_submitted_on'></td></tr>
                  <tr><th>District: </th><td id='reject_query_district'></td></tr>
                  <tr><th>Query:</th><td id="reject_question_descr"></td></tr>
                  <tr><th>Attachments:</th><td></td></tr>
                </tbody>
              </table>
            </div>

            <form id="formRejectQuery">
              @csrf
                 <input type="hidden" id="reject_query_id" name="reject_query_id"  value="">
                 <div class="row">
                  <label for="queryRejectReason" class="col-sm-2 col-form-label"><strong> Reason: </strong></label>
                  <div class="col-sm-10">
                    <div class="input-group input-group-merge">
                      <span id="queryRejectReasonIcon" class="input-group-text"><i class="bx bx-comment"></i></span>
                      <textarea id="queryRejectReason" name="queryRejectReason" class="form-control" placeholder="Please give reason for rejecting the query" required></textarea>
                    </div>
                  </div>
                </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-danger" id="rejectQuerybtn" >
                Reject
              </button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Close
              </button>

            </form>
          </div>

        </div>
      </div>
    </div>
@endsection

@section('custom_js')

<script>

    // Open View Modal
    $(document).ready( function () {
        const allElements = document.querySelectorAll('*');
                    allElements.forEach(el => {
                        el.style.fontSize = '14px';
                    });
        $('#tblQueries').DataTable();

        $('#queryCategoryManagerBtn').on('click', function(){
          // alert("Test Works");
          $('#manageQueryCategoryModal').modal('show');
        });

        $("#tblQueries").on("click", ".OpenViewModalBtn", function(){
          // console.log("Working...");
          var question = $(this).data('query_desc');
          var ack_no = $(this).data('ack_no');
          const query_id = $(this).data('query_id');
          var submitted_by = $(this).data('query_submitted_by');
          var submitted_on = $(this).data('query_submitted_on');
          var district = $(this).data('query_district');

          $('#view_query_id').val(query_id);
          $('#view_ack_no').html(ack_no);
          $('#view_query_submitted_by').html(submitted_by);
          $('#view_query_submitted_on').html(submitted_on);
          $('#view_query_district').html(district);
          $('#view_question_descr').html(question);
          $('#viewQuestionModal').modal('show');
        });


    // Open Accept Query Modal
      $("#tblQueries").on("click", ".OpenAcceptModalBtn", function(){
          // console.log('Accept Button Working..');
          var question = $(this).data('query_desc');
          var ack_no = $(this).data('ack_no');
          const query_id = $(this).data('query_id');
          var submitted_by = $(this).data('query_submitted_by');
          var submitted_on = $(this).data('query_submitted_on');
          var district = $(this).data('query_district');

          $('#accept_query_id').val(query_id);
          $('#accept_ack_no').html(ack_no);
          $('#accept_query_submitted_by').html(submitted_by);
          $('#accept_query_submitted_on').html(submitted_on);
          $('#accept_query_district').html(district);
          $('#accept_question_descr').html(question);

          var url_str = 'linkparentquery/'+ query_id;
          $("#linkQueryButton").attr("href", url_str);

          $('#acceptQuestionModal').modal('show');
      });

    // Open Reject Query Modal
      $("#tblQueries").on("click", ".OpenRejectModalBtn", function(){
          // console.log('Reject Button Working..');
          var question = $(this).data('query_desc');
          var ack_no = $(this).data('ack_no');
          const query_id = $(this).data('query_id');
          var submitted_by = $(this).data('query_submitted_by');
          var submitted_on = $(this).data('query_submitted_on');
          var district = $(this).data('query_district');

          $('#reject_query_id').val(query_id);
          $('#reject_ack_no').html(ack_no);
          $('#reject_query_submitted_by').html(submitted_by);
          $('#reject_query_submitted_on').html(submitted_on);
          $('#reject_query_district').html(district);
          $('#reject_question_descr').html(question);
          $('#rejectQuestionModal').modal('show');
      });


      // Create Category AAJX
      $('#createCategoryBtn').click(function(e){
        e.preventDefault();
        $.ajax ({
          url: '{{route('moderator.createcategory')}}',
          data: $('#formCreateCategory').serialize(),
          type: "POST",
          headers: {
            'X-CSRF-Token': '{{ csrf_token() }}',
          },
          success: function(data){
            if(data.status == 1) {
              alert(data.message);
              $('#manageQueryCategoryModal').modal('hide');
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


      // Accept Query AJAX
      $('#acceptQuerybtn').click(function(e){
        e.preventDefault();
          $.ajax({
              url: '{{ route('moderator.acceptquery') }}',
              data: $("#formAcceptQuery").serialize(),
              type: "POST",
              headers: {
                  'X-CSRF-Token': '{{ csrf_token() }}',
              },
              success: function(data){
                if(data.status == 1) {
                  // alert("Query Accepted.");
                  alert(data.message);
                  $('#acceptQuestionModal').modal('hide');
                  location.reload();
                }
                else if(data.status == 0) {
                  alert(data.message);
                }
              },
              error: function(){
                    alert("Query moderation failed.Something went wrong!");
              }
          });
        });


        // Reject Query AJAX
      $('#rejectQuerybtn').click(function(e){
        e.preventDefault();
          $.ajax({
              url: '{{ route('moderator.rejectquery') }}',
              data: $("#formRejectQuery").serialize(),
              type: "POST",
              headers: {
                  'X-CSRF-Token': '{{ csrf_token() }}',
              },
              success: function(data){
                if( data.status == 1 ) {
                  // alert("Query Rejected.");
                  alert(data.message);
                  $('#rejectQuestionModal').modal('hide');
                  location.reload();
                }
                else if( data.status == 0) {
                  alert(data.message);
                }
              },
              error: function(){
                    alert("Query moderation failed. Something went wrong!");
              }
          });
        });

        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })

    } );


</script>
@endsection

