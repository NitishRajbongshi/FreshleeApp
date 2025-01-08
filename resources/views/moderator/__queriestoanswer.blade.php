@extends('moderator.common.layout')

@section('title', 'Answer Queries')

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
                    <button class="btn btn-sm btn-outline-primary OpenViewModalBtn"
                    data-crop_type_cd="{{Crypt::encrypt($item->query_id)}}"
                    data-crop_type_desc = "{{$item->query_desc}}">
                    <i class='bx bx-show'></i>View</button>

                    <button class="btn btn-sm btn-outline-success acceptBtn"
                    data-crop_type_cd="{{Crypt::encrypt($item->query_id)}}">
                    <i class='bx bx-check-circle'></i>Answer</button>

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

@endsection

@section('custom_js')
<script>
    $(document).ready( function () {
        const allElements = document.querySelectorAll('*');
                    allElements.forEach(el => {
                        el.style.fontSize = '14px';
                    });
        $('#tblQueriesToAnswer').DataTable();
    } );
</script>
@endsection

