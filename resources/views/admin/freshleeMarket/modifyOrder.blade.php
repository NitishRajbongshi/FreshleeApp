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
    <div class="row justify-content-between">
        <div class="col-12 col-md-4 mt-1">
            <div class="card mb-1">
                <div class="card-body">
                    <h5 class="text-md lh-1">Modify User Order</h5>
                    <div class="lh-1 text-sm">
                        <p class="lh-sm">
                            Customer: <span class="fw-bold text-md">{{ $customer_name }}</span><br>
                            Booking ID: <span class="fw-bold">{{ $booking_id }}</span>
                        </p>
                        <div class="">
                            <button class="btn btn-sm btn-primary" id="add_btn">
                                <i class="tf-icons bx bx-plus-medical me-1 text-xs"></i>Add New Item
                            </button>
                            <a href="{{ route('admin.user.order') }}" class="text-xs text-primary">Back to order list</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 mt-1">
            <div class="card mb-1" id="add_item_form" style="display: none;">
                <h5 class="card-header text-md">Available List of Items</h5>
                <div class="card-body row px-4 pb-2 text-sm">
                    <form action="{{ route('admin.user.order.create') }}" method="POST" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="booking_id" value="{{ $booking_id }}">
                            <div class="mb-3 col-sm-12 col-md-6">
                                <label class="form-label" for="item_cd">Items</label><br>
                                <select style="width: 100%;"
                                    class="form-select form-select-sm @error('item_cd') is-invalid @enderror" id="item_cd"
                                    name="item_cd" required>
                                    <option value="">Select Item</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->item_cd }}" data-unit="{{ $item->unit_min_order_qty }}"
                                            {{ old('item_cd') == $item->item_cd ? 'selected' : '' }}>
                                            {{ $item->item_name }} (Min. order: {{ $item->min_qty_to_order }}
                                            {{ $item->unit_min_order_qty }})
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
                                <label class="form-label" for="item_quantity">Quantity</label>
                                <input type="number" step="0.01"
                                    class="form-control form-control-sm @error('item_quantity') is-invalid @enderror"
                                    id="item_quantity" name="item_quantity" placeholder="0.00"
                                    value="{{ old('item_quantity') }}" required>
                                @error('item_quantity')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-sm-12 col-md-2">
                                <label class="form-label" for="qty_unit">Unit</label>
                                <select class="form-select form-select-sm @error('qty_unit') is-invalid @enderror"
                                    id="qty_unit" name="qty_unit" required>
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
                </div>
            </div>
            <div class="card">
                <div class="card-body text-sm">
                    <h5 class="text-md lh-1">Placed Orders
                        <br>
                        <span class="text-xs text-secondary">Showing all the items placed by the customer.</span>
                    </h5>
                    @if ($user_orders->isEmpty())
                        <p>No orders placed yet.</p>
                    @else
                        @foreach ($user_orders as $order)
                            <div class="row align-items-center mb-2">
                                <div class="col-12 col-md-6 d-flex align-items-center">
                                    <i class='bx bxs-circle text-sm me-2'></i>
                                    {{ $order->item_name }}
                                </div>
                                <div class="row col-12 col-md-6">
                                    <div class="col-10">
                                        <form action="{{ route('admin.user.order.update') }}" method="POST"
                                            class="d-flex gap-1">
                                            @csrf
                                            <input type="hidden" class="form-control form-control-sm" name="item_id"
                                                value="{{ $order->id }}">
                                            <input type="hidden" class="form-control form-control-sm" name="item_cd"
                                                value="{{ $order->item_cd }}">
                                            <input type="hidden" class="form-control form-control-sm" name="booking_id"
                                                value="{{ $order->booking_ref_no }}">
                                            <input type="number" step="0.01" class="form-control form-control-sm"
                                                name="item_quantity" value="{{ $order->item_quantity }}" required>
                                            <select class="form-select form-select-sm" name="item_unit">
                                                <option value="{{ $order->qty_unit }}" selected>{{ $order->unit_desc }}
                                                </option>
                                                @foreach ($itemUnits as $unit)
                                                    @if ($order->qty_unit != $unit->unit_cd)
                                                        <option value="{{ $unit->unit_cd }}">
                                                            {{ $unit->unit_desc }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <button type="submit" class="submit btn btn-sm btn-primary">
                                                <i class='bx bx-pen'></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-2">
                                        <form action="{{ route('admin.user.order.delete') }}" method="post">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $order->id }}">
                                            <button type="submit" class="btn btn-sm btn-danger delete-btn"
                                                onclick="return confirm('Are you sure!!')">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
            $('#add_btn').on('click', function() {
                $("#add_item_form").toggle();
            })

            $('#item_cd').select2({
                dropdownParent: $('#add_item_form') // Attach dropdown to the modal
            });
            $(document).on('select2:open', function(e) {
                document.querySelector(`[aria-controls="select2-${e.target.id}-results"]`).focus();
            });

            $('#item_cd').on('change', function(event) {
                event.preventDefault();
                $("#qty_unit").val("");
                $('#unit_kg, #unit_gm, #unit_ltr, #unit_ml, #unit_unit, #unit_mutha').hide();
                var itemUnit = $.trim($('#item_cd').find(':selected').data('unit'));
                switch (itemUnit) {
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

            //Prevent Multiple Submissions
            $('form').on('submit', function() {
                $(this).find('button[type="submit"]').prop('disabled', true);
            });

        })
    </script>
@endsection
