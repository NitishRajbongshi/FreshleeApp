@extends('admin.common.layout')

@section('title', 'Agri Medicinal Products')

@section('custom_header')
@endsection

@section('main')

@if ($message = Session::get('success'))
    <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card text-dark-black">
    <div class="d-flex align-items-center">
        <h5 class="card-header">Agri Medicinal Products Details</h5>
        <div>
            <a href="#" class="btn btn-outline-success">
                <i class="tf-icons bx bx-plus-medical"></i>Add New
            </a>
        </div>
    </div>
    <div class="row my-1 justify-content-end align-item-center">
        <div class="col-sm-6 col-md-3 d-flex justify-content-end my-1">
            <span class="mdcn_prod"></span>
        </div>
    </div>
    <div class="table-responsive text-nowrap text-dark-black">
        <table class="table table-responsive table-bordered table-stripped text-nowrap " id="tblMdcn">
            <thead>
                <tr>
                    <th>Sl. No.</th>
                    <th>Technical Code</th>
                    <th>Technical Name</th>
                    <th>Trade Code</th>
                    <th>Trade Name</th>
                    <th>Product Type</th>
                    <th>Manufacturer Name</th>
                    <th>Product Status</th>
                    <th>Is Registered </th>
                    <th>View Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-xs text-dark-black">
                @foreach ($medicialProdDetails as $item)
                    <tr>
                        <td>{{ 1 }}</td>
                        <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->technical_code }}</td>
                        <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->technical_name }}</td>
                        <td> {{ $item->trade_code }}</td>
                        <td> {{ $item->trade_name }}</td>
                        <td> {{ $item->product_type_descr }}</td>
                        <td> {{ $item->manufacturer_name }}</td>
                        <td> {{ $item->status_descr }}</td>
                        <td> {{ $item->is_registered }}</td>
                        <td>View Image</td>
                        <td><a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal"
                                data-bs-target="#editModal" data-tech-cd="{{ $item->technical_code }}"
                                data-tech-name="{{ $item->technical_name }}" data-trade-cd="{{ $item->trade_code }}"
                                data-trade-name="{{ $item->trade_name }}" data-prod-type-cd="{{ $item->product_type_cd }}"
                                data-prod-type-desc="{{ $item->product_type_descr }}"
                                data-manufacturer-name="{{ $item->manufacturer_name }}" data-status-cd="{{ $item->status }}"
                                data-status-desc="{{ $item->status_descr }}"
                                data-is-registered="{{ $item->is_registered }}">
                                <i class="tf-icons bx bx-edit"></i> Edit
                            </a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Agri Medicinal Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST" action="#" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="tech_code" class="form-label">Tech Code:</label>
                            <input type="text" class="form-control" id="tech_code" name="tech_code" value="" />
                        </div>
                        <div class="col-md-6">
                            <label for="tech_name" class="form-label">Tech Name:</label>
                            <input type="text" class="form-control" id="tech_name" name="tech_name" value="" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="trade_code" class="form-label">Trade Code:</label>
                            <input type="text" class="form-control" id="trade_code" name="trade_code" value="" />
                        </div>
                        <div class="col-md-6">
                            <label for="trade_name" class="form-label">Trade Name:</label>
                            <input type="text" class="form-control" id="trade_name" name="trade_name" value="" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="prd_type" class="form-label">Product Type:</label>
                            <input type="text" class="form-control" id="prd_type" name="prd_type" value="" />
                        </div>
                        <div class="col-md-6">
                            <label for="manufc_name" class="form-label">Manufacturer Name:</label>
                            <input type="text" class="form-control" id="manufc_name" name="manufc_name" value="" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('custom_js')
<script type="module" src="{{ asset('admin_assets/js/admin/medicinalProducts.js') }}"></script>
<script>
    var prod_types_master = @json($prod_type_dtls);
</script>
@endsection