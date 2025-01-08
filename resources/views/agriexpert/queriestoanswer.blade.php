@extends('agriexpert.common.layout')

@section('title', '[Agri-Expert] Answer Queries')

@section('custom_header')
@endsection

@section('main')

<div class="card">
    <div class="d-flex align-items-center">
        <h5 class="card-header">Query Viewer</h5>
        {{-- <div>
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
            data-bs-target="#createCropTypeModal">
              <i class="tf-icons bx bx-plus-medical"></i>
                XXXXXX
            </button>
        </div> --}}
    </div>

    <div class="table-responsive text-nowrap px-4">
        <table class="table" id="tblQueriesToAnswer">
          <thead>
            <tr>
              <th>No.</th>
              <th>ACK No.</th>
              <th>Query</th>
              <th>Category</th>
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
                  <td>{{ $item->ack_no }}</td>
                  <td><div class='text-wrap'>{{ $item->query_desc }}</div></td>
                  <td>{{ $item->queryCategory->catg_descr ?? 'None' }}</td>
                  <td>{{ $item->district }}</td>
                  <td>
                    @if ( $item->has_attachment )
                        {{ "Yes" }}
                    @else
                        {{ "No" }}
                    @endif
                  </td>
                  <td>
                    {{-- <button class="btn btn-sm btn-outline-primary OpenViewModalBtn"
                    data-crop_type_cd="{{Crypt::encrypt($item->query_id)}}"
                    data-crop_type_desc = "{{$item->query_desc}}">
                    <i class='bx bx-show'></i>View</button> --}}

                    <button class="btn btn-sm btn-outline-primary OpenViewModalBtn"
                    data-query_id="{{ Crypt::encrypt($item->query_id) }}"
                    data-query_submitted_by = "{{ $item->query_submitted_by }}"
                    data-query_submitted_on = "{{ $item->query_submitted_on }}"
                    data-query_district = "{{ $item->district }}"
                    data-query_desc = "{{$item->query_desc}}"
                    data-ack_no = "{{ $item->ack_no }}"
                    data-district = "{{ $item->district }}""
                    data-toggle="tooltip" data-placement="top" title="View Query">
                    <i class='bx bx-show'></i>View</button>

                    <button class="btn btn-sm btn-outline-success OpenAnswerModalBtn"
                    data-query_id="{{ Crypt::encrypt($item->query_id) }}"
                    data-query_submitted_by = "{{ $item->query_submitted_by }}"
                    data-query_submitted_on = "{{ $item->query_submitted_on }}"
                    data-query_district = "{{ $item->district }}"
                    data-query_desc = "{{$item->query_desc}}"
                    data-ack_no = "{{ $item->ack_no }}"
                    data-district = "{{ $item->district }}""
                    data-toggle="tooltip" data-placement="top" title="Accept Query">
                    <i class='bx bx-check-circle'></i>Answer</button>

                    {{-- <button class="btn btn-sm btn-outline-success acceptBtn"
                    data-crop_type_cd="{{Crypt::encrypt($item->query_id)}}">
                    <i class='bx bx-check-circle'></i>Answer</button> --}}

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
        </div>
        <div class="modal-footer">

          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Close
          </button>
        </div>

      </div>
    </div>
  </div>

  {{-- Answer Question Modal --}}
  <div class="modal fade" id="answerQuestionModal" tabindex="-1" aria-hidden="true" style="display: none;">
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
                <tr><th>ACK No.: </th><td id="answer_ack_no"></td></tr>
                <tr><th>Submitted By: </th><td id='answer_query_submitted_by'></td></tr>
                <tr><th>Submitted On: </th><td id='answer_query_submitted_on'></td></tr>
                <tr><th>District: </th><td id='answer_query_district'></td></tr>
                <tr><th>Query:</th><td id="answer_question_descr"></td></tr>
                <tr><th>Attachments:</th>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>

          <form id="formAnswerQuery">
            @csrf
               <input type="hidden" id="answer_query_id" name="answer_query_id"  value="">
               {{-- <div class="row">
                <label for="queryCategorySelect" class="col-sm-3 col-form-label"><strong> Category: </strong></label>
                <div class="col-sm-9">
                    <select class="form-select" id="queryCategorySelect" name="queryCategorySelect" aria-label="Select Query Category" required>
                      <option selected value="" >Select Category</option>
                      @foreach ($categories as $category)
                        <option value="{{ $category->catg_id }}">{{ $category->catg_descr }}</option>
                      @endforeach
                    </select>
                  </div>
               </div> --}}

               <div class="row mt-2">
                <label for="queryAnswer" class="col-sm-3 col-form-label"><strong> Write Answer: </strong></label>
                <div class="col-sm-9">
                    <textarea rows="4" class="form-control" id="queryAnswer" name="queryAnswer" aria-label="Write an answer"></textarea>
                </div>
               </div>
        </div>
        {{-- End of Modal Body --}}

        <div class="modal-footer">

          <button type="submit" class="btn btn-success" id="answerQueryBtn" >
            Submit Answer
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Close
          </button>
        </div>
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
        $('#tblQueriesToAnswer').DataTable();

        $("#tblQueriesToAnswer").on("click", ".OpenViewModalBtn", function(){
          // alert("Test Works");
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

        $('#tblQueriesToAnswer').on("click", ".OpenAnswerModalBtn", function(){

          var question = $(this).data('query_desc');
          var ack_no = $(this).data('ack_no');
          const query_id = $(this).data('query_id');
          var submitted_by = $(this).data('query_submitted_by');
          var submitted_on = $(this).data('query_submitted_on');
          var district = $(this).data('query_district');

          $('#answer_query_id').val(query_id);
          $('#answer_ack_no').html(ack_no);
          $('#answer_query_submitted_by').html(submitted_by);
          $('#answer_query_submitted_on').html(submitted_on);
          $('#answer_query_district').html(district);
          $('#answer_question_descr').html(question);

          $('#answerQuestionModal').modal('show');
        });

        // Accept Query AJAX
      $('#answerQueryBtn').click(function(e){

        e.preventDefault();
        // data= $("#formAnswerQuery").serialize();
        // alert(data);
          $.ajax({
              url: '{{ route('agriexpert.answerquery') }}',
              data: $("#formAnswerQuery").serialize(),
              type: "POST",
              headers: {
                  'X-CSRF-Token': '{{ csrf_token() }}',
              },
              success: function(data){
                if(data.status == 1) {
                  // alert("Query Accepted.");
                  alert(data.message);
                  $('#answerQuestionModal').modal('hide');
                  location.reload();
                }
                else if(data.status == 0) {
                  alert(data.message);
                }
              },
              error: function(){
                    alert("Query answering failed.Something went wrong!");
              }
          });
        });


        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })
    } );
</script>
@endsection

