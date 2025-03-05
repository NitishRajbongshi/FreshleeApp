@extends('admin.common.layout')
@section('title', 'User Role Management')
@section('custom_header')
@endsection
@section('main')
    @if ($message = Session::get('success'))
        <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div id="errorAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row justify-content-between">
        <div class="col-12 col-md-4 mt-1">
            <div class="card">
                <div class="card-header text-md lh-1">Store Info
                    {{-- <h5 class="text-md lh-1"> --}}
                    {{-- <br> --}}
                    {{-- <span class="text-xs text-secondary">
                        </span> --}}
                    {{-- </h5> --}}
                </div>
                <div class="card-body row px-4 pb-4 text-sm">
                    <div class="col-3">Store</div>
                    <div class="col-9 text-secondary"><span>: </span><span id="">Vidhi Store</span></div>
                    <div class="col-3">Address1</div>
                    <div class="col-9 text-secondary"><span>: </span><span id="">Zoo Road Tiniali</span></div>
                    <div class="col-3">Address2</div>
                    <div class="col-9 text-secondary"><span>: </span><span id="">Guwahati</span></div>
                    <div class="col-3">PIN</div>
                    <div class="col-9 text-secondary"><span>: </span><span id="">781021</span></div>
                    <div class="col-3">Status</div>
                    <div class="col-9 text-info"><span>: </span><span id="">Active</span></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 mt-1">
            <div class="card" id="user_info_container">
                <div class="card-header">
                    <h5 class="text-md lh-1">Store Inventory
                        <br>
                        <span class="text-xs text-info">Listing all items between Monday to Sunday</span>
                    </h5>
                </div>
                <div class="card-body row px-4 pb-2 text-sm">
                    <form action="{{ route('admin.inventory.store') }}" method="POST" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-sm-12 col-md-4">
                                <label class="form-label" for="item_cd">Items</label>
                                <select class="form-select form-select-sm" id="item_cd" name="item_cd" required>
                                    <option value="">Select Item</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->item_cd }}" data-name="{{ $item->item_name }}"
                                            data-unit="{{ $item->item_price_in }}"
                                            {{ old('item_cd') == $item->item_cd ? 'selected' : '' }}>
                                            {{ $item->item_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('item_cd')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-sm-12 col-md-2">
                                <label class="form-label" for="qty">Qty</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="qty"
                                    name="qty" placeholder="0.00" value="{{ old('qty') }}" required>
                                @error('qty')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-sm-12 col-md-2">
                                <label class="form-label" for="qty_unit">Unit</label>
                                <select class="form-select form-select-sm" id="qty_unit" name="qty_unit" required>
                                    <option value="">Select</option>
                                    @foreach ($itemUnits as $unit)
                                        <option value="{{ $unit->unit_cd }}" id="unit_{{ $unit->unit_cd }}"
                                            style="display: none;"
                                            {{ old('qty_unit') == $unit->unit_cd ? 'selected' : '' }}>
                                            {{ $unit->unit_desc }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('qty_unit')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-sm-12 col-md-2">
                                <label class="form-label" for="price_per_kg">Price / KG</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="price_per_kg"
                                    name="price_per_kg" placeholder="0.00" value="{{ old('price_per_kg') }}" required>
                                @error('price_per_kg')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-sm-12 col-md-2 d-flex align-items-end justify-content-end">
                                <button type="submit" class="btn btn-sm btn-primary">Add Item</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successAlert = document.getElementById('successAlert');

            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.opacity = '0';
                    successAlert.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(function() {
                        successAlert.remove();
                    }, 500);
                }, 5000);
            }

            var errorAlert = document.getElementById('errorAlert');

            if (errorAlert) {
                setTimeout(function() {
                    errorAlert.style.opacity = '0';
                    errorAlert.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(function() {
                        errorAlert.remove();
                    }, 500);
                }, 5000);
            }
        });

        $(document).ready(function() {
            $('#tblUser').DataTable();
            $("#item_cd").select2();
            $(document).on('select2:open', function(e) {
                document.querySelector(`[aria-controls="select2-${e.target.id}-results"]`).focus();
            });

            $('#item_cd').on('change', function(event) {
                var item_unit = $('#item_cd option:selected').data('unit');
                $("#qty_unit").val("");
                $('#unit_kg, #unit_gm, #unit_ltr, #unit_ml, #unit_unit, #unit_mutha').hide();
                switch (item_unit) {
                    case 'kg':
                        $('#unit_kg').show();
                        break;
                        
                    case 'gm':
                        $('#unit_gm').show();
                        break;
                        
                    case 'ltr':
                        $('#unit_ltr').show();
                        break;

                    case 'ml':
                        $('#unit_ml').show();
                        break;

                    case 'mutha':
                        $('#unit_mutha').show();
                        break;

                    case 'unit':
                        $('#unit_unit').show();
                        break;

                    default:
                        break;
                }
            })
        });
    </script>
@endsection
