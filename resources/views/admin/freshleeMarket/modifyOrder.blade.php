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

    <div class="card mb-1">
        <div class="card-body">
            <h5 class="text-md lh-1">Modify User Order</h5>
            <hr>
            <div class="row justify-content-center lh-1">
                <div class="col-12 col-md-8 text-sm">
                    <p class="lh-sm">
                        Booking ID: <span class="fw-bold bg-warning">{{ $booking_id }}</span><br>
                        Customer Name: <span class="fw-bold text-md">{{ $customer_name }}</span>
                    </p>
                </div>
                <div class="col-12 col-md-4 text-end">
                    <button class="btn btn-sm btn-outline-success" id="add_btn">
                        <i class="tf-icons bx bx-plus-medical me-1 text-xs"></i>Add New Item
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-1" id="add_item_form" style="display: none;">
        <div class="card-body lh-1">
            <form action="{{ route('admin.user.order.create') }}" method="POST" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking_id }}">
                <div class="row">
                    <div class="mb-3 col-sm-12 col-md-4">
                        <label class="form-label" for="item_cd">Item Name</label>
                        <select class="form-select form-select-sm" id="item_cd" name="item_cd" required>
                            <option value="">Select Item</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->item_cd }}"
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
                        <label class="form-label" for="item_quantity">Item Quantity</label>
                        <input type="number" step="0.01" class="form-control form-control-sm" id="item_quantity" name="item_quantity"
                            placeholder="0.00" value="{{ old('item_quantity') }}" required>
                        @error('item_quantity')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 col-sm-12 col-md-3">
                        <label class="form-label" for="qty_unit">Item Unit</label>
                        <select class="form-select form-select-sm" id="qty_unit" name="qty_unit" required>
                            <option value="">Select Unit</option>
                            @foreach ($itemUnits as $unit)
                                <option value="{{ $unit->unit_cd }}"
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
            @foreach ($user_orders as $order)
                <div class="row align-items-center mb-2">
                    <div class="col-12 col-md-6 d-flex align-items-center">
                        <i class='bx bxs-circle text-sm me-2'></i>
                        {{ $order->item_name }}
                    </div>
                    <div class="row col-12 col-md-6">
                        <div class="col-10">
                            <form action="{{ route('admin.user.order.update') }}" method="POST" id="update_order_table"
                                class="d-flex gap-1">
                                @csrf
                                <input type="hidden" class="form-control form-control-sm" name="item_id" value="{{ $order->id }}">
                                <input type="hidden" class="form-control form-control-sm" name="item_cd" value="{{ $order->item_cd }}">
                                <input type="hidden" class="form-control form-control-sm" name="booking_id"
                                    value="{{ $order->booking_ref_no }}">
                                <input type="number" step="0.01" class="form-control form-control-sm" name="item_quantity"
                                    value="{{ $order->item_quantity }}" required>
                                <select class="form-select form-select-sm" id="item_unit" name="item_unit">
                                    <option value="{{ $order->qty_unit }}" selected>{{ $order->unit_desc }}</option>
                                    @foreach ($itemUnits as $unit)
                                        @if ($order->qty_unit != $unit->unit_cd)
                                            <option value="{{ $unit->unit_cd }}">
                                                {{ $unit->unit_desc }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <button type="submit" class="submit btn btn-sm btn-primary"
                                    data-id="{{ $order->item_cd }}">
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
        })

        // $(document).ready(function() {
        //     $('#update_order_table').on('submit', function(e) {
        //         e.preventDefault();

        //         var form = $(this);
        //         var url = form.attr('action');
        //         var method = form.attr('method');
        //         var data = form.serialize();

        //         $.ajax({
        //             url: url,
        //             type: method,
        //             data: data,
        //             success: function(response) {
        //                 if (response.success) {
        //                     alert('Order updated successfully!');
        //                 } else {
        //                     alert('Failed to update order.');
        //                 }
        //             },
        //             error: function(xhr) {
        //                 alert('An error occurred while updating the order.');
        //             }
        //         });
        //     });
        // });
    </script>
@endsection
