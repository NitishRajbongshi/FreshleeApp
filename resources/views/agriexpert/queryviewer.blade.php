@extends('agriexpert.common.layout')

@section('title', '[Agri-Expert] Answer Queries')

@section('custom_header')
@endsection

@section('main')

@if (isset($message))
<div class="alert alert-danger alert-dismissible" role="alert">
  {{ $message }}
  {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
</div>
@endif

  <div class="row mb-4">
    <div class="card">
      <div class="card-body">
        <label for="queryCategorySelect" class="form-label col-md-2">Select Category</label>
        <div class="col-md-4">
          <select class="form-select" id="queryCategorySelect" name="queryCategorySelect" aria-label="Select Query Category" required>
            <option  value="" >All Categories</option>
            @foreach ($categories as $category)
                  @if(isset($selectedId) && $selectedId == $category->catg_id)
                    <option selected value="{{ Crypt::encrypt($category->catg_id) }}">{{ $category->catg_descr }}</option>
                  @else
                    <option value="{{ Crypt::encrypt($category->catg_id) }}">{{ $category->catg_descr }}</option>
                  @endif
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>

  @if(strlen( $queries->links() )>0)
    <div class="row mb-4">
      <div class="card">
        <div class="card-body">
          {!! $queries->links() !!}
        </div>
      </div>
    </div>
  @endif

  <div class="row">
    @forelse ($queries as $index => $item)

    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title text-primary">[{{$index + 1}}]. AKC. NO: {{ $item->ack_no }}</h5>
        <div class="card-subtitle text-muted mb-3">Submitted By {{$item->query_submitted_by}},
          on {{ date('d-m-Y H:i:s', strtotime($item->query_submitted_on)) }},
          from {{$item->district}}</div>
        <p class="card-text text-wrap">
          {{$item->query_desc}}
        </p>
        <div class="float-end">
        {{-- <a href="javascript:void(0)" class="card-link btn btn-primary">Card link</a> --}}

        <button class="btn btn-sm btn-outline-primary OpenViewModalBtn"
                    data-query_id="{{ Crypt::encrypt($item->query_id) }}"
                    data-query_submitted_by = "{{ $item->query_submitted_by }}"
                    data-query_submitted_on = "{{ $item->query_submitted_on }}"
                    data-query_district = "{{ $item->district }}"
                    data-query_desc = "{{$item->query_desc}}"
                    data-ack_no = "{{ $item->ack_no }}"
                    data-district = "{{ $item->district }}"
                    data-toggle="tooltip" data-placement="top" title="Quick View">
                    <i class='bx bx-show'></i>Quick View</button>

        {{-- <a href="javascript:void(0)" class="card-link btn btn-info">Another link</a> --}}

        @if($item->parent_ack_no == null)
          <button class="btn btn-sm btn-outline-success OpenAnswerModalBtn"
                    data-query_id="{{ Crypt::encrypt($item->query_id) }}"
                    data-query_submitted_by = "{{ $item->query_submitted_by }}"
                    data-query_submitted_on = "{{ $item->query_submitted_on }}"
                    data-query_district = "{{ $item->district }}"
                    data-query_desc = "{{$item->query_desc}}"
                    data-ack_no = "{{ $item->ack_no }}"
                    data-district = "{{ $item->district }}"
                    data-toggle="tooltip" data-placement="top" title="Write Answer">
                    <i class='bx bx-check-circle'></i>Write Answer</button>
        @else
          <a href="{{route('agriexpert.loadthread', ['id' => Crypt::encrypt($item->parent_ack_no)])}}"
            class="btn btn-sm btn-outline-info">View Parent Thread</a>
        @endif

          <a class="btn btn-sm btn-outline-secondary OpenAnswerThreadBtn"
                    {{-- data-query_id="{{ Crypt::encrypt($item->query_id) }}"
                    data-toggle="tooltip" data-placement="top" title="View Thread" --}}
                    href="{{route('agriexpert.loadthread', ['id' => Crypt::encrypt($item->query_id)])}}">
                    <i class='bx bx-blanket'></i>View Thread</a>
        </div>
      </div>
    </div>



    @empty
      <div class="card">
        <div class="card-body">
          <div class="card-text">No queries found.</div>
        </div>
      </div>
    @endforelse
  </div>

  @if(strlen( $queries->links() )>0)
  <div class="row mb-4">
    <div class="card">
      <div class="card-body">
        {!! $queries->links() !!}
      </div>
    </div>
  </div>
  @endif


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

        // $("#tblQueriesToAnswer").on("click", ".OpenViewModalBtn", function(){
        $('.OpenViewModalBtn').click( function() {
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

        // $('#tblQueriesToAnswer').on("click", ".OpenAnswerModalBtn", function(){
        $('.OpenAnswerModalBtn').click(function(){

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

      // Answer Query AJAX
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

        $('#queryCategorySelect').change( function(){
          var category = $('#queryCategorySelect').val() ;
          var path = window.location.origin + '/query-answer/agri-expert/queryviewer/'+category;
          window.location.href = path;
        });


        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })
    } );
</script>
@endsection

