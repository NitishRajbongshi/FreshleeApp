@extends('agriexpert.common.layout')

@section('title', '[Agri-Expert] View Thread')

@section('custom_header')
@endsection

@section('main')
@if (isset($message))
  <div class="alert alert-danger alert-dismissible" role="alert">
    {{ $message }}
    {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
  </div>
@else

<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title">AKC. NO: {{ $query->ack_no }}</h5>
    </div>

    <div class="card-body">
      <p class="card-text text-wrap">
        {{$query->query_desc}}
      </p>
    </div>

    <div class="card-footer">

        <div class="row">
            <div class="card-subtitle text-muted col-lg-8 col-mb-8 col-sm-8">
                Submitted By <span class="text-info">{{$query->query_submitted_by}}</span>,
                on {{ date('d-m-Y H:i:s', strtotime($query->query_submitted_on)) }},
                from {{$query->district}}
            </div>

            <div class="col-lg-4 col-mb-4 col-sm-4">
                <div class="float-end">
                    {{-- <a href="javascript:void(0)" class="card-link btn btn-primary">Reply Now</a> --}}
                  @if($query->parent_ack_no == null)
                    <button class="btn btn-md btn-primary OpenAnswerModalBtn"
                    data-query_id="{{ Crypt::encrypt($query->query_id) }}"
                    data-query_submitted_by = "{{ $query->query_submitted_by }}"
                    data-query_submitted_on = "{{ $query->query_submitted_on }}"
                    data-query_district = "{{ $query->district }}"
                    data-query_desc = "{{$query->query_desc}}"
                    data-ack_no = "{{ $query->ack_no }}"
                    data-district = "{{ $query->district }}"
                    data-toggle="tooltip" data-placement="top" title="Write Answer">
                    <i class='bx bx-check-circle'></i>Reply To Query</button>
                    @else
                      <a href="{{route('agriexpert.loadthread', ['id' => Crypt::encrypt($query->parent_ack_no)])}}"
                        class="btn btn-md btn-info">View Parent Thread</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@if(isset($answers))
    @foreach($answers as $answer)
        <div class="card mb-3 offset-1">
            {{-- <div class="card-header">
                {{ $answer['ans_by']}}
            </div> --}}
            <div class="card-body">
                <p class="card-title">Answer Ack. No.:
                    <span class="text-info">{{$answer['ans_ack_no']}}</span></p>
                <div class="card-text">
                    {{$answer['query_ans']}}
                </div>
            </div>
            <div class="card-footer">
                <div class="card-subtitle text-muted">
                    Answered By <span class="text-info">{{$answer->ans_by}}</span>
                    on {{ date('d-m-Y H:i:s', strtotime($answer->ans_on)) }}
                </div>
            </div>
        </div>
    @endforeach
@endif
@endif

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
$(document).ready(function(){

    const allElements = document.querySelectorAll('*');
                    allElements.forEach(el => {
                        el.style.fontSize = '14px';
                    });

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

});
</script>
@endsection
