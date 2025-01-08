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

    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="container-fluid mt-0">

                    <h5 class="card-header">MIS Report</h5>
                    <div class="card-body">
                        <form class="frmGenerateReport" id="frmGenerateReport" method="POST"
                            action="{{ route('getMISReport') }}">
                            @csrf
                            <div class="row col-md-12">
                                <div class="col-md-2 mb-1">
                                    <label for="name" class="col-form-label">From Date:</label>
                                    <input type="date" value="{{ date('Y-m-d', strtotime($date_last_30_days)) }}"
                                        name="frm_date" id="frm_date" class="form-control" required>
                                </div>
                                <div class="col-md-2 mb-1">
                                    <label for="name" class="col-form-label">To Date:</label>
                                    <input type="date" value="{{ date('Y-m-d', strtotime($todays_date)) }}"
                                        name="to_date" id="to_date" class="form-control" required>
                                </div>
                                <div class="col-md-2 mb-1">
                                    <label for="name" class="col-form-label">District</label>
                                    <select name="sel_dist_cd" id="sel_dist_cd" class="text-xs form-control">
                                        <option value="A" selected>All District</option>
                                        @foreach ($distMaster as $item)
                                            @if ($item->state_cd == '1')
                                                <option value="{{ $item->district_cd }}" class="text-xs text-uppercase">
                                                    {{ $item->district_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-1">
                                    <label for="name" class="col-form-label">Disease</label>
                                    <select name="sel_disease_cd" id="sel_disease_cd" class="text-xs form-control">
                                        <option value="A" selected>All Disease</option>
                                        @foreach ($digeaseMaster as $item)
                                            <option value="{{ $item->disease_cd }}" class="text-xs text-uppercase">
                                                {{ $item->disease_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-1">
                                    <label for="name"
                                        class="col-form-label">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</label>
                                    <button type="submit" class="btn btn-primary" style="border-radius:5px"><i
                                            class="fa-solid fa-floppy-disk"></i> Get Report</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @if (!$initialPage)
            <br>
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="container-fluid mt-0">
                        <div class="d-flex align-items-center">
                            <h5 class="card-header">MIS Report From {{ $user_select_from_date }} to
                                {{ $user_select_to_date }}</h5>
                        </div>
                        <div class="card-body">
                            <span class="exportDataTable text-center"></span>
                            <div class="row text-center">

                                <table class="table-responsive table table-bordered table-striped text-center"
                                    id="tblNews">
                                    <thead class="theader text-white" style="background-color:#8a9fb6">
                                        <tr>
                                            <th>Sl. No.</th>
                                            <th>Date</th>
                                            <th>Location</th>
                                            <th>District</th>
                                            <th>Infestation</th>
                                            <th>Farmers Contact No.</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php $i = 1; ?>

                                        @foreach ($listDiagnosedDisease as $key)
                                            <tr>
                                                <td class="text-center">{{ $i }}</td>
                                                <td style="position: relative">
                                                    {{ $key->requested_on }}
                                                </td>
                                                <td style="position: relative">
                                                    {{ $key->locality_name }}
                                                </td>
                                                <td style="position: relative">
                                                    {{ $key->district_name }}
                                                </td>
                                                <td style="position: relative">
                                                    {{ $key->disease_name }}
                                                </td>
                                                <td style="position: relative">
                                                    {{ $key->phone }}
                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection {{-- End of main --}}

@section('custom_js')
    <script>
        $(document).ready(function() {
            const allElements = document.querySelectorAll('*');
        allElements.forEach(el => {
            el.style.fontSize = '14px';
        });
            $('#tblNews').DataTable({
                "buttons": [{
                        extend: 'copy',
                        className: 'btn btn-primary glyphicon glyphicon-duplicate'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-primary glyphicon glyphicon-save-file'
                    }, ,
                    {
                        extend: 'excel',
                        className: 'btn btn-primary glyphicon glyphicon-save-file'
                    }, ,
                    {
                        extend: 'pdf',
                        className: 'btn btn-primary glyphicon glyphicon-save-file'
                    }
                ]
            }).buttons().container().appendTo('.exportDataTable');
        });
    </script>
@endsection
