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
        <div class="m-4">
            <div class="d-flex flex-wrap justify-content-between">
                <div class="col-12 col-md-6 text-sm">
                    <h5 class="text-md">Customer Details</h5>
                    <p class="card-text lh-1">
                        Booking ID:
                        <span class="text-secondary">
                            {{ $booking_id }}
                        </span>
                    </p>
                    <p class="card-text lh-1">
                        Customer Name:
                        <span class="text-secondary">
                            {{ $cust_name }}
                        </span>
                    </p>
                    <p class="card-text lh-1">
                        Customer Phone:
                        <span class="text-secondary">
                            +91 {{ $cust_phone }}
                        </span>
                    </p>
                    <a href="{{route("admin.user.order")}}" class="my-2 btn btn-sm btn-primary">Back to order list</a>
                </div>
                <div class="col-12 col-md-6 text-sm">
                    <h5 class="text-md">Ordered Item List</h5>
                    <form id="itemForm">
                        <div class="my-2 text-danger">
                            <input type="checkbox" id="ckbDeliveryChargeFifty"  value="50" onclick="toggleCheckbox(this, 'ckbDeliveryChargeSeventyFive')"/> Delivery Charge Rs.50
                            <input type="checkbox" id="ckbDeliveryChargeSeventyFive" value="75" onclick="toggleCheckbox(this, 'ckbDeliveryChargeFifty')"/> Delivery Charge Rs.75
                            <input type="checkbox" id="ckbShoppingBag" value="30"/> Shopping Bag Rs.30
                        </div>
                        <div class="my-2 text-danger">
                            <input type="checkbox" id="ckbCheckAll" /> Select all items
                        </div>
                        
                        <ul class="list-group" style="list-style-type: none;" id="item-list">
                            @if (empty($priceList))
                                <li>No items found.</li>
                            @else
                                @foreach ($priceList as $item)
                                    <li class="list-group-item lh-1">
                                        <label>
                                            <input type="checkbox" class="item-checkbox"
                                                data-item-id="{{ $item['item_cd'] }}" data-name="{{ $item['item_name'] }}" 
                                                data-quantity="{{ $item['item_quantity'] }}"
                                                data-qty-unit="{{ $item['qty_unit'] }}"
                                                data-price-per-kg="{{ $item['price_per_kg'] }}"
                                                data-total-price="{{ $item['total_price'] }}"
                                                data-price-unit="{{ $item['item_price_in_unit'] }}">
                                            {{ $item['item_name'] }} ({{ $item['item_quantity'] }}
                                            {{ $item['qty_unit'] }})
                                            - Price: Rs. {{ $item['total_price'] }}
                                        </label>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        <div style="text-align: right;">
                            <button type="button" class="my-2 btn btn-sm btn-primary" id="calculateBill">
                                Calculate Bill
                            </button>
                            <button type="button" id="markDelivered" class="my-2 btn btn-sm btn-warning">
                                Mark As Delivered
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card d-none" id="billDetails">
        <div class="m-4">
            <div>
                <div class="mb-2 text-md">
                    <span class="mr-2"><strong>Booking ID:</strong>
                        <span>{{ $booking_id }}</span>
                    </span>
                    <span><strong>Customer Name:</strong> {{ $cust_name }}</span>
                </div>

                <table id="billTable" class="table table-striped table-bordered text-sm">
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
                            <th colspan="3" class="p-1 text-end pe-3 text-sm">Delivery Charge</th>
                            <th id="txtDeliveryCharge" class="p-1 text-end pe-3 text-sm">Rs. 0</th>
                        </tr>
                        <tr class="">
                            <th colspan="3" class="p-1 text-end pe-3 text-sm">Shopping Bag Charge</th>
                            <th id="txtShoppingCharge" class="p-1 text-end pe-3 text-sm">Rs. 0</th>
                        </tr>
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
                <input type="hidden" name="customer_phone" value="{{ $cust_phone }}">
                <input type="hidden" name="hdnDeliveryCharge" id ="hdnDeliveryCharge" value="">
                <input type="hidden" name="hdnBagCharge" id ="hdnBagCharge" value="">
                <input type="hidden" name="total_amount" id="total_amount">
                <div id="selectedItemsContainer"></div>
                <div style="text-align: right;">
                    <button type="submit" class="btn btn-sm btn-warning mt-3">Download Invoice</button>
                </div>
            </form>
        </div>

    </div>
@endsection

@section('custom_js')
    <script>
        function toggleCheckbox(current, otherId) {
            const otherCheckbox = document.getElementById(otherId);
            if (current.checked) {
                otherCheckbox.checked = false;
            }
        }

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

            // start - script to select all items
            // When the "Select All" checkbox is clicked
            $('#ckbCheckAll').click(function() {
                // Check or uncheck all checkboxes with the class 'itemCheckbox'
                $('.item-checkbox').prop('checked', this.checked);
            });
            // When any of the individual checkboxes are clicked
            $('.item-checkbox').click(function() {
                // If all individual checkboxes are checked, check the "Select All" checkbox
                if ($('.item-checkbox:checked').length === $('.item-checkbox').length) {
                    $('#ckbCheckAll').prop('checked', true);
                } else {
                    $('#ckbCheckAll').prop('checked', false);
                }
            });
            // end

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
                var delvCharge = 0;
                var bagCharge = 0;
                if($("#ckbDeliveryChargeFifty").is(":checked"))
                    delvCharge = parseFloat($("#ckbDeliveryChargeFifty").val());
                
                if($("#ckbDeliveryChargeSeventyFive").is(":checked"))
                    delvCharge = parseFloat($("#ckbDeliveryChargeSeventyFive").val());

                if($("#ckbShoppingBag").is(":checked"))
                bagCharge = parseFloat($("#ckbShoppingBag").val());
                
                $('#txtDeliveryCharge').text("Rs." + delvCharge);
                $('#txtShoppingCharge').text("Rs." + bagCharge);
                $('#invoiceForm #hdnDeliveryCharge').text("Rs." + delvCharge); 
                $('#invoiceForm #hdnBagCharge').text("Rs." + bagCharge); 
                totalAmount = totalAmount + delvCharge + bagCharge;
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
                    Swal.fire(
                        'Waring!',
                        'Select atleast one item',
                        'error'
                    );
                    return;
                }

                // Show a confirmation dialog using SweetAlert
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to mark these items as delivered?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, mark as delivered!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
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
                                Swal.fire(
                                    'Delivered!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred. Please try again.',
                                    'error'
                                );
                                console.error(xhr.responseText); // Log error for debugging
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire(
                            'Cancelled',
                            'Items are not marked as delivered.',
                            'error'
                        );
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

                var delvCharge = 0;
                var bagCharge = 0;
                if($("#ckbDeliveryChargeFifty").is(":checked"))
                    delvCharge = parseFloat($("#ckbDeliveryChargeFifty").val());
                
                if($("#ckbDeliveryChargeSeventyFive").is(":checked"))
                    delvCharge = parseFloat($("#ckbDeliveryChargeSeventyFive").val());

                if($("#ckbShoppingBag").is(":checked"))
                    bagCharge = parseFloat($("#ckbShoppingBag").val());
                
                $('#invoiceForm #hdnDeliveryCharge').val(delvCharge); 
                $('#invoiceForm #hdnBagCharge').val(bagCharge);
                totalAmount = totalAmount + delvCharge + bagCharge;

                $('#total_amount').val(totalAmount.toFixed(2));
            }
        });
    </script>
@endsection
