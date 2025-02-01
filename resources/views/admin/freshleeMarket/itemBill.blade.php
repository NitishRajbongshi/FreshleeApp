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

    <div class="card mb-2">
        <div class="m-4">
            <div class="d-flex flex-wrap justify-content-between">
                <div class="col-12 col-md-6 text-sm">
                    <h5 class="text-underline text-md">Customer Details</h5>
                    <p class="card-text">
                        Booking ID:
                        <span class="text-secondary">
                            {{ $booking_id }}
                        </span>
                    </p>
                    <p class="card-text">
                        Customer Name:
                        <span class="text-secondary">
                            {{ $cust_name }}
                        </span>
                    </p>
                    <p class="card-text">
                        Customer Phone:
                        <span class="text-secondary">
                            +91 {{ $cust_phone }}
                        </span>
                    </p>
                </div>
                <div class="col-12 col-md-6 text-sm">
                    <h5 class="text-underline text-md">Ordered Item List</h5>
                    <form id="itemForm">
                        <ul class="list-group" style="list-style-type: none;" id="item-list">
                            @foreach ($priceList as $item)
                                <li class="list-group-item">
                                    <label>
                                        <input type="checkbox" class="item-checkbox" data-item-id="{{ $item['item_cd'] }}"
                                            data-name="{{ $item['item_name'] }}"
                                            data-quantity="{{ $item['item_quantity'] }}"
                                            data-qty-unit="{{ $item['qty_unit'] }}"
                                            data-price-per-kg="{{ $item['price_per_kg'] }}"
                                            data-total-price="{{ $item['total_price'] }}"
                                            data-price-unit="{{ $item['item_price_in_unit'] }}">
                                        {{ $item['item_name'] }} ({{ $item['item_quantity'] }} {{ $item['qty_unit'] }})
                                        - Price: Rs. {{ $item['total_price'] }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                        <div style="text-align: right;">
                            <button type="button" class="my-2 btn btn-primary" id="calculateBill">Calculate Bill</button>
                            <button type="button" id="markDelivered" class="my-2 btn btn-warning">Mark as
                                Delivered</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card my-2 d-none" id="billDetails">
        <div class="m-4">
            <div>
                <div class="mb-2 text-md">
                    <span class="mr-2"><strong>Booking ID:</strong> {{ $booking_id }}</span>
                    <span><strong>Customer Name:</strong> {{ $cust_name }}</span>
                </div>

                <table id="billTable" class="table table-striped table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th class="p-1 text-start">Item Name</th>
                            <th class="p-1 text-end pe-3">Quantity</th>
                            <th class="p-1 text-end pe-3">Price</th>
                            <th class="p-1 text-end pe-3">Total Price</th>
                        </tr>
                    </thead>
                    <tbody id="billTableBody">
                    </tbody>
                    <tfoot>
                        <tr class="">
                            <th colspan="3" class="p-1 text-end pe-3 text-sm">Total Amount</th>
                            <th id="totalAmount" class="p-1 text-end pe-3 text-sm">Rs. 0</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <form action="{{ route('generate.invoice') }}" method="POST" id="invoiceForm">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking_id }}">
                <input type="hidden" name="customer_name" value="{{ $cust_name }}">
                <input type="hidden" name="total_amount" id="total_amount">
                <div id="selectedItemsContainer"></div>
                <div style="text-align: right;">
                    <button type="submit" class="btn btn-warning mt-3">Download Invoice</button>
                </div>
            </form>
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
            // Calculate Bill Button Click Handler
            $('#calculateBill').on('click', function() {
                updateBillTable();
                createHiddenItemInputs();
            });

            // Mark Delivered Button Click Handler
            $('#markDelivered').on('click', function() {
                markItemsAsDelivered();
            });

            // Handle the invoice form submission
            $('#invoiceForm').on('submit', function(e) {
                e.preventDefault();
                createHiddenItemInputs();
                this.submit();
            })

            // Function to update bill table
            function updateBillTable() {
                let totalAmount = 0;
                const billTableBody = $('#billTableBody');
                billTableBody.empty();

                $('#item-list input.item-checkbox:checked').each(function() {
                    const $item = $(this);
                    const name = $item.data('name');
                    const quantity = $item.data('quantity');
                    const qtyUnit = $item.data('qty-unit');
                    const pricePerKg = $item.data('price-per-kg');
                    const priceUnit = $item.data('price-unit');
                    const totalPrice = $item.data('total-price');

                    totalAmount += parseFloat(totalPrice);

                    billTableBody.append(`
                        <tr class="text-center">
                            <td class="p-1 text-start">${name}</td>
                            <td class="p-1 text-end pe-3">${quantity} ${qtyUnit}</td>
                            <td class="p-1 text-end pe-3">Rs. ${pricePerKg.toFixed(2)} per ${priceUnit}</td>
                            <td class="p-1 text-end pe-3">Rs. ${totalPrice.toFixed(2)}</td>
                        </tr>
                    `);
                });

                $('#totalAmount').text(`Rs. ${totalAmount.toFixed(2)}`);
                $('#billDetails').removeClass('d-none').show();
            }

            // Function to mark items as delivered
            function markItemsAsDelivered() {
                const selectedItems = [];
                $('#item-list input.item-checkbox:checked').each(function() {
                    selectedItems.push($(this).data('item-id'));
                });

                if (selectedItems.length === 0) {
                    alert('Please select at least one item to mark as delivered.');
                    return;
                }
                const deliveryStatus = confirm('OK to continue?');
                if (!deliveryStatus) {
                    alert('Item not mark as delivered.');
                    return;
                }

                $.ajax({
                    url: "{{ route('order.delivered') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {
                        booking_id: "{{ $booking_id }}",
                        item_cds: selectedItems,
                    },
                    success: function(response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred. Please try again.');
                        console.error(xhr.responseText); // Log error for debugging
                    }
                });
            }


            // Function to create hidden input fields
            function createHiddenItemInputs() {
                let selectedItemsContainer = $('#selectedItemsContainer');
                selectedItemsContainer.empty(); // Clear previous inputs
                let totalAmount = 0;

                $('#item-list input.item-checkbox:checked').each(function() {
                    const $item = $(this);
                    const itemId = $item.data('item-id');
                    const name = $item.data('name');
                    const quantity = $item.data('quantity');
                    const qtyUnit = $item.data('qty-unit');
                    const pricePerKg = $item.data('price-per-kg');
                    const priceUnit = $item.data('price-unit');
                    const totalPrice = $item.data('total-price');

                    totalAmount += parseFloat(totalPrice);

                    selectedItemsContainer.append(`
                    <input type="hidden" name="items[${itemId}][name]" value="${name}">
                    <input type="hidden" name="items[${itemId}][quantity]" value="${quantity}">
                    <input type="hidden" name="items[${itemId}][qty_unit]" value="${qtyUnit}">
                    <input type="hidden" name="items[${itemId}][price_per_kg]" value="${pricePerKg}">
                    <input type="hidden" name="items[${itemId}][price_unit]" value="${priceUnit}">
                    <input type="hidden" name="items[${itemId}][total_price]" value="${totalPrice}">
                    `);
                });

                $('#total_amount').val(totalAmount.toFixed(2));
            }
        });
    </script>
@endsection
