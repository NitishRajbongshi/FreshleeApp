@extends('admin.common.layout')
@section('title', 'User Role Management')
@section('custom_header')
@endsection
@section('main')
    <div class="row justify-content-between">
        <div class="col-12 col-md-4 mt-1">
            <div class="card">
                <div class="card-header lh-1">
                    <h2 class="text-lg">
                        <i class='bx bxs-user-circle text-xl'></i>
                    </h2>
                    <h5 class="text-md lh-1"> {{ $customer_name }}
                        <br>
                        <span class="text-xs text-secondary">
                            {{ $customer_phone }}
                        </span>
                    </h5>
                </div>
                <div class="card-body lh-1">
                    <a href="{{ route('admin.proxy.user.list') }}" class="text-sm">Change User</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 mt-1">
            <div class="card" id="user_info_container">
                <div class="card-header">
                    <h5 class="text-md lh-1">Available List of Items
                        <br>
                        <span class="text-xs text-secondary">Showing all the items availble for this week</span>
                    </h5>
                </div>
                <div class="card-body row px-4 pb-2 text-sm">
                    <form action="{{ route('admin.place.order') }}" method="GET" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="hidden" id="input_user_name" name="name" value="{{ $customer_name }}">
                            <input type="hidden" id="input_user_phone" name="phone" value="{{ $customer_phone }}">
                            <input type="hidden" id="input_pin_code" name="pin" value="{{ $customer_pin }}">
                            <input type="hidden" id="input_addr_code" name="address" value="{{ $customer_address }}">
                            <input type="hidden" id="input_item_name" name="item_name" value="">
                            <input type="hidden" id="input_min_order" name="item_min_order" value="">
                            <input type="hidden" id="input_min_qty" name="item_min_qty" value="">
                            <input type="hidden" id="input_min_unit" name="item_min_unit" value="">
                            <div class="mb-3 col-sm-12 col-md-6">
                                <label class="form-label" for="item_cd">Items</label>
                                <select class="form-select form-select-sm" id="item_cd" name="item_cd" required>
                                    <option value="">Select Item</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item['item_cd'] }}" data-name="{{ $item['item_name'] }}"
                                            data-min_order_qty="{{ $item['min_order_qty'] }}"
                                            data-min_qty="{{ $item['min_qty'] }}" data-min_unit="{{ $item['unit'] }}"
                                            {{ old('item_cd') == $item['item_cd'] ? 'selected' : '' }}>
                                            {{ $item['item_name'] }} (Min. Order: {{ $item['min_order_qty'] }})
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
                            <div class="mb-3 col-sm-12 col-md-2 d-flex align-items-end justify-content-end">
                                <button type="submit" class="btn btn-sm btn-primary">Add Item</button>
                            </div>
                        </div>
                    </form>

                    {{-- List all cart items --}}
                    @if (count($order_items) == 0)
                        <p class="text-info"><i class='bx bxs-sad'></i> Cart Is Empty!</p>
                    @else
                        <p class="text-primary"><i class='bx bx-cart-download'></i>User Cart</p>
                        <ul class="list-group ps-2" style="list-style-type: none;" id="item-list">
                            @foreach ($order_items as $item)
                                <li class="list-group-item lh-1">
                                    {{ $item['item_name'] }} (Min. Order: {{ $item['item_min_order'] }}) :
                                    {{ $item['item_qty'] }} {{ $item['item_unit'] }}
                                </li>
                            @endforeach
                        </ul>
                        <div class="my-2">
                            <form action="{{ route('admin.place.order') }}" method="POST">
                                @csrf
                                <input type="hidden" id="cust_name" name="name" value="{{ $customer_name }}">
                                <input type="hidden" id="cust_phone" name="phone" value="{{ $customer_phone }}">
                                <input type="hidden" id="cust_pin" name="pin" value="{{ $customer_pin }}">
                                <input type="hidden" id="cust_addr" name="address" value="{{ $customer_address }}">
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Click OK to confirm order')">Confirm Order</button>
                            </form>
                        </div>
                    @endif

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
        });

        $(document).ready(function() {
            $('#tblUser').DataTable();
            $("#item_cd").select2();
            $(document).on('select2:open', function(e) {
                document.querySelector(`[aria-controls="select2-${e.target.id}-results"]`).focus();
            });
            $('#item_cd').on('change', function(event) {
                var item_cd = $('#item_cd').val();
                var item_name = $('#item_cd option:selected').data('name');
                var item_min_order_qty = $('#item_cd option:selected').data('min_order_qty');
                var item_min_qty = $('#item_cd option:selected').data('min_qty');
                var item_min_unit = $('#item_cd option:selected').data('min_unit');
                $('#input_item_name').val(item_name);
                $('#input_min_order').val(item_min_order_qty);
                $('#input_min_qty').val(item_min_qty);
                $('#input_min_unit').val(item_min_unit);

                $("#qty_unit").val("");
                $('#unit_kg, #unit_gm, #unit_ltr, #unit_ml, #unit_unit, #unit_mutha').hide();
                switch (item_min_unit) {
                    case 'kg':
                    case 'gm':
                        $('#unit_kg, #unit_gm').show();
                        break;

                    case 'ltr':
                    case 'ml':
                        $('#unit_ltr, #unit_ml').show();
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
