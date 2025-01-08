@extends('moderator.common.layout')

@section('title', 'Moderate Queries')

@section('custom_header')
@endsection

@section('main')

<div class="card mb-4">
    <div class="d-flex align-items-center">
        <h5 class="card-header">Parent Query Selector</h5>
        <a href="{{route('moderator.queries')}}" class="btn btn-outline-info">
            <i class="tf-icons bx bxs-chevrons-left"></i>Back To Query Moderation
        </a>
    </div>
</div>

    {{-- @forelse ($selectedQuery as $index => $item) --}}
@if ($selectedQuery)

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title text-primary">AKC. NO: {{ $selectedQuery->ack_no }}</h5>
        <div class="card-subtitle text-muted mb-3">Submitted By {{$selectedQuery->query_submitted_by}},
        on {{ date('d-m-Y H:i:s', strtotime($selectedQuery->query_submitted_on)) }},
        from {{$selectedQuery->district}}</div>
        <p class="card-text text-wrap">
        {{$selectedQuery->query_desc}}
        </p>
    </div>
</div>

@else
<div class="card">
    <div class="card-body">
    <div class="card-text">No query found.</div>
    </div>
</div>
@endif

<div class="card">
    <div class="table-responsive text-nowrap px-4 py-4">
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
                    data-district = "{{ $item->district }}""
                    data-toggle="tooltip" data-placement="top" title="View Query">
                    <i class='bx bx-show'></i></button>

                    <button class="btn btn-sm btn-outline-success setAsParentQueryBtn"
                    id="setAsParentQueryBtn"
                    data-child_id ="{{ Crypt::encrypt($selectedQuery->query_id) }}"
                    data-query_id="{{ Crypt::encrypt($item->query_id) }}"
                    data-toggle="tooltip" data-placement="top" title="Set As Parent Query">
                    <i class='bx bx-check-circle'></i></button>
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
        <div class="modal-footer">

          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Close
          </button>
        </div>

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
    $('#tblQueries').DataTable();

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

    $("#tblQueries").on("click", ".setAsParentQueryBtn", function(){
        // console.log("Working...");
        const query_id = $(this).data('query_id');
        const child_id = $(this).data('child_id');

        var formDat = {
            parent_id: query_id,
            child_id: child_id
        };

        $.ajax({
            type: "post",
            url: "{{ route('moderator.setparentquery') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formDat,
            success: function (data) {
                // if(data.status == 2 ) {
                //   $('#checkStatus').prop('checked', 'checked');
                //   alert(data.message);
                //   console.log(data);
                // }
                // else
                // {
                //   console.log(data);
                // }
                console.log(data);
                alert(data.message);
            }
        });
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
});
</script>
@endsection