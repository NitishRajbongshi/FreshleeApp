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
    <p class="text-danger border border-danger p-2:">This module is under development phase. Please do not perform any operation</p>
    <div class="row justify-content-between">
        <div class="col-12 col-md-7 mt-1">
            <div class="card" id="user_info_container">
                <div class="card-header">
                    <h5 class="text-md lh-1">Store Inventory
                        <br>
                        <span class="text-xs text-info">Add items to the inventory</span>
                    </h5>
                </div>
                <div class="card-body row px-4 pb-2 text-sm">
                    <form action="{{ route('admin.inventory.store') }}" method="POST" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-sm-12 col-md-6">
                                <label class="form-label" for="item_cd">Purchased Items <span
                                        class="text-xs text-danger">*</span></label>
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
                            <div class="mb-3 col-sm-12 col-md-3">
                                <label class="form-label" for="item_qty">Item Quantities <span
                                        class="text-xs text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="item_qty"
                                    name="item_qty" placeholder="0.00" value="{{ old('item_qty') }}" required>
                                @error('item_qty')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-sm-12 col-md-3">
                                <label class="form-label" for="item_unit">Item Unit <span
                                        class="text-xs text-danger">*</span></label>
                                <select class="form-select form-select-sm" id="item_unit" name="item_unit" required>
                                    <option value="">Select</option>
                                    @foreach ($itemUnits as $unit)
                                        <option value="{{ $unit->unit_cd }}" id="unit_{{ $unit->unit_cd }}"
                                            style="display: none;"
                                            {{ old('item_unit') == $unit->unit_cd ? 'selected' : '' }}>
                                            {{ $unit->unit_desc }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('item_unit')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-sm-12 col-md-3">
                                <label class="form-label" for="purchase_date">Purchase Date <span
                                        class="text-xs text-danger">*</span></label>
                                <input type="date" class="form-control form-control-sm" id="purchase_date"
                                    name="purchase_date" value="{{ old('purchase_date') }}" required>
                                @error('purchase_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-sm-12 col-md-3">
                                <label class="form-label" for="item_price">Price / unit <span
                                        class="text-xs text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="item_price"
                                    name="item_price" placeholder="0.00" value="{{ old('item_price') }}" required>
                                @error('item_price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-sm-12 col-md-3">
                                <label class="form-label" for="purchase_discount">Discount (Rs.)</label>
                                <input type="number" step="0.01" class="form-control form-control-sm"
                                    id="purchase_discount" name="purchase_discount" placeholder="0.00"
                                    value="{{ old('purchase_discount') ? old('purchase_discount') : 0.0 }}">
                                @error('purchase_discount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-sm-12 col-md-3">
                                <label class="form-label" for="total_price">Total Price <span
                                        class="text-xs text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control form-control-sm"
                                    id="total_price" name="total_price" placeholder="0.00"
                                    value="{{ old('total_price') }}" required>
                                @error('total_price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-sm-12 col-md-6">
                                <label class="form-label" for="farmer_name">Farmer Name</label>
                                <input type="text" class="form-control form-control-sm" id="farmer_name"
                                    name="farmer_name" placeholder="John Doe" value="{{ old('farmer_name') }}">
                                @error('farmer_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-sm-12 col-md-6 d-flex align-items-end justify-content-end">
                                <button type="submit" class="btn btn-sm btn-primary">Add Item</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            {{-- List all cart items --}}
            <div class="card" id="user_info_container">
                <div class="card-header">
                    <h5 class="text-md lh-1">Store Inventory
                        <br>
                        <span class="text-xs text-info">Add items to the inventory</span>
                    </h5>
                </div>
                <div class="card-body row px-4 pb-2 text-sm">
                    @if (count($inventory) == 0)
                        <p class="text-warning">No item added!!</p>
                    @else
                        <p class="text-primary"><i class='bx bx-cart-download'></i>Item List</p>
                        <div class="mb-1 text-end">
                            <button class="btn btn-sm btn-danger">
                                <i class="tf-icons bx bxs-trash me-1 text-xs"></i>Clear All
                            </button>
                            <button class="btn btn-sm btn-primary" id="add_btn">
                                <i class="tf-icons bx bx-plus-medical me-1 text-xs"></i>Add Expenditure
                            </button>
                        </div>
                        <ul class="list-group ps-2" style="list-style-type: none;" id="item-list">
                            @foreach ($inventory as $item)
                                <li class="list-group-item lh-1">
                                    {{ $item['item_cd'] }}
                                </li>
                            @endforeach
                        </ul>
                        {{-- <div class="my-2">
                    <form action="{{ route('admin.place.order') }}" method="POST">
                        @csrf
                        <input type="hidden" id="cust_name" name="name" value="{{ $customer_name }}">
                        <input type="hidden" id="cust_phone" name="phone" value="{{ $customer_phone }}">
                        <input type="hidden" id="cust_pin" name="pin" value="{{ $customer_pin }}">
                        <input type="hidden" id="cust_addr" name="address" value="{{ $customer_address }}">
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Click OK to confirm order')">Confirm Order</button>
                    </form>
                </div> --}}
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 col-md-5 mt-1">
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
                    <div class="col-9 text-secondary"><span>: </span><span id="">Zoo Tiniali, Geetanagar,
                            Guwahati</span></div>
                    <div class="col-3">PIN</div>
                    <div class="col-9 text-secondary"><span>: </span><span id="">781021</span></div>
                    <div class="col-3">Status</div>
                    <div class="col-9 text-info"><span>: </span><span id="">Active</span></div>
                </div>
            </div>

            <div class="card mt-2" style="display: none;" id="add_item_form">
                <div class="card-header text-md lh-1">
                    Add expenditure
                </div>
                <div class="card-body px-4 pb-4 text-sm">
                    <form action="{{ route('admin.order.report.history') }}" method="POST">
                        @csrf
                        {{-- <div class="row align-items-end text-sm">
                            <div class="col-12 col-md-5">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input class="form-control form-control-sm" type="date" name="start_date">
                            </div>
                            <div class="col-12 col-md-5">
                                <label for="end_date" class="form-label">End Date</label>
                                <input class="form-control form-control-sm" type="date" name="end_date">
                            </div>
                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-sm btn-primary">View</button>
                            </div>
                        </div> --}}
                        <p>Expenditure form will appear here.</p>
                    </form>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header text-md lh-1">
                    Search Inventory History
                </div>
                <div class="card-body px-4 pb-4 text-sm">
                    <form action="{{ route('admin.order.report.history') }}" method="POST">
                        @csrf
                        <div class="row align-items-end text-sm">
                            <div class="col-12 col-md-5">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input class="form-control form-control-sm" type="date" name="start_date">
                            </div>
                            <div class="col-12 col-md-5">
                                <label for="end_date" class="form-label">End Date</label>
                                <input class="form-control form-control-sm" type="date" name="end_date">
                            </div>
                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-sm btn-primary">View</button>
                            </div>
                        </div>
                    </form>
                    <div class="mt-2">
                        <form action="" id="customerForm" method="POST">
                            @csrf
                            <div class="row align-items-end text-sm">
                                <div class="col-sm-12 col-md-9">
                                    <select class="form-select form-select-sm" id="customer" name="customer">
                                        <option value="">Select History Record</option>
                                        {{-- @foreach ($customers as $customer)
                                                <option value="{{ $customer->phone_no }}"
                                                    data-name="{{ $customer->full_name }}"
                                                    {{ old('customer') == $customer->phone_no ? 'selected' : '' }}>
                                                    {{ $customer->full_name }} - {{ $customer->phone_no }}
                                                </option>
                                            @endforeach --}}
                                    </select>
                                </div>
                                <div class="col-sm-12 col-md-3 text-end">
                                    <button type="submit" class="btn btn-sm btn-primary">Continue</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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

            $('#add_btn').on('click', function() {
                $("#add_item_form").fadeToggle("slow");
            })

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

            // calculate total price
            $('#item_price, #purchase_discount').on('input', function() {
                var item_qty = parseFloat($('#item_qty').val());
                var item_price = parseFloat($('#item_price').val());
                var purchase_discount = parseFloat($('#purchase_discount').val());
                var total_price = (item_qty * item_price) - purchase_discount;
                $('#total_price').val(total_price.toFixed(2));
            });
        });
    </script>
@endsection
