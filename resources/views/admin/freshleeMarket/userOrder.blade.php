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

    <div class="card">
        <h5 class="card-header text-md lh-1"><span class="underline">Default Pickup Address</span></h5>
        <div class="card-body d-flex flex-wrap justify-content-between text-sm">
            <div style="line-height: 1px;">
                <p class="card-text">
                    Address 1:
                    <span class="text-secondary">
                        {{ isset($picupAddress) && is_object($picupAddress) ? $picupAddress->address_line1 : 'N/A' }}
                    </span>
                </p>
                <p class="card-text">
                    State:
                    <span class="text-secondary">
                        {{ isset($picupAddress) && is_object($picupAddress) && $picupAddress->state_cd == 'AS' ? 'Assam' : 'NA' }}
                    </span>
                </p>
            </div>
            <div style="line-height: 1px;">
                <p class="card-text">
                    Address 2:
                    <span class="text-secondary">
                        {{ isset($picupAddress) && is_object($picupAddress) ? $picupAddress->address_line2 : 'N/A' }}
                    </span>
                </p>
                <p class="card-text">
                    District:
                    <span class="text-secondary">
                        {{ isset($picupAddress) && is_object($picupAddress) && $picupAddress->district_cd == 'KM' ? 'Kamrup(Metro)' : 'NA' }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="card my-1">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="text-md lh-1 ">User Order Details
                <br>
                <span class="text-xs text-secondary">Listing all the items between <span class="text-primary">
                        {{ $start }}</span> (Monday) to
                    <span class="text-primary">{{ $today }}</span> (Present Day).</span>
            </h5>
            <div>
                <a href="{{ route('admin.proxy.user.list') }}" class="btn btn-sm btn-primary">
                    Place New Order
                </a>
            </div>
            <div class="d-flex align-items-center">
                <div>
                    <a href="{{ route('admin.order.report') }}" class="text-decoration-underline text-xs">
                        Weekly Report
                    </a>
                </div>
                <form action="{{ route('admin.order.history') }}" method="POST">
                    @csrf
                    <input type="hidden" id="start_date" name="start_date" value="{{ $first }}">
                    <input type="hidden" id="end_date" name="end_date" value="{{ $today }}">
                    <button type="submit"
                        class="btn btn-md text-xs btn-outline-none text-danger text-decoration-underline">
                        Order History
                    </button>
                </form>
            </div>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-2">
            <table class="border table text-xs" id="tblUser">
                <thead>
                    <tr class="text-center">
                        <th>Customer Details</th>
                        <th>Ordered Date</th>
                        <th>Item + Quantity</th>
                        <th>Order Status</th>
                        <th>Customer Order</th>
                        <th>Billing</th>
                        <th style="display: none;">Order Info</th>
                        {{-- <th>Delivery Status</th> --}}
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php
                        $serialNumber = 1; // Initialize serial number counter
                    @endphp
                    @foreach ($data as $index => $item)
                        <tr class="text-center">
                            <td style="overflow-wrap: break-word; white-space: normal;">
                                <span>{{ $item->full_name }}</span><br>
                                <span>Ph: +91 {{ $item->phone_no }}</span>
                            </td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->order_date }}</td>
                            <td style="overflow-wrap: break-word; text-align: left;">
                                <ol>
                                    @php
                                        $ordered_items = json_decode($item->order_items, true);
                                    @endphp
                                    @foreach ($ordered_items as $ordered_item)
                                        <li>{{ $ordered_item['item_name'] }}:
                                            <span style="font-weight: bold;">{{ $ordered_item['item_quantity'] }}</span>
                                            {{ $ordered_item['qty_unit'] }}
                                        </li>
                                    @endforeach
                                </ol>
                                <div class="lh-1 ps-3 d-flex align-items-center">
                                    <p class="btn btn-xs btn-outline-secondary price_btn"
                                        data-ref_id="{{ $item->booking_ref_no }}">
                                        Get Price
                                    </p>
                                    <p id="{{ $item->booking_ref_no }}_loader_gif" style="display: none;">
                                        <img src="{{ asset('admin_assets\img\gif\loader.gif') }}" alt="loader"
                                            width="22px;">Calculating..
                                    </p>
                                    <p id="{{ $item->booking_ref_no }}_price_tab" style="display: none;"
                                        class="fw-bold ms-1">
                                        Rs. <span id="{{ $item->booking_ref_no }}_total_price"></span>
                                    </p>
                                </div>
                            </td>
                            <td style="overflow-wrap: break-word; white-space: normal;">
                                {{ $item->is_delivered == 'Y' ? 'Delivered' : 'Pending' }}
                            </td>
                            <td>
                                @if ($item->is_delivered == 'Y')
                                <i class='bx bx-block text-xs text-danger'></i>
                                @else
                                    <form action="{{ route('admin.user.order.modify') }}" method="GET">
                                        @csrf
                                        <input type="hidden" name="cust_id" value="{{ $item->cust_id }}" id="cust_id">
                                        <input type="hidden" name="cust_name" value="{{ $item->full_name }}"
                                            id="cust_id">
                                        <input type="hidden" name="booking_id" value="{{ $item->booking_ref_no }}"
                                            id="booking_id">
                                        <button type="submit" class="btn btn-xs btn-outline-primary" style="padding: 2px;">
                                            <i class='bx bx-pen'></i> Modify
                                        </button>
                                    </form>
                                @endif

                            </td>
                            <td>
                                <form action="{{ route('order.billing') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cust_id" value="{{ $item->cust_id }}" id="cust_id">
                                    <input type="hidden" name="cust_name" value="{{ $item->full_name }}" id="cust_name">
                                    <input type="hidden" name="cust_phone" value="{{ $item->phone_no }}" id="cust_phone">
                                    <input type="hidden" name="booking_id" value="{{ $item->booking_ref_no }}"
                                        id="booking_id">
                                    <input type="hidden" name="order_items" value="{{ $item->order_items }}"
                                        id="order_items">
                                    <button type="submit" class="btn btn-xs btn-outline-primary" style="padding: 2px;">
                                        <i class='bx bx-add-to-queue'></i> Generate
                                    </button>
                                </form>
                            </td>
                            <td style="display: none; overflow-wrap: break-word; white-space: normal;">
                                {{ $item->booking_ref_no }}
                            </td>
                            {{--  // Place to Add back the delivery update code

                                    <td>
                                         <a href="#" class="btn btn-xs py-1 btn-outline-primary edit-btn" data-bs-toggle="modal"
                                             data-bs-target="#editModal" data-booking-id="{{ $item->booking_ref_no }}"
                                             data-customer-name="{{ $item->full_name }}"
                                             data-delivery-status="{{ $item->is_delivered }}"
                                             data-delivery-at="{{ $item->delivered_at != null ? $item->delivered_at : $today }}">
                                             <i class="tf-icons bx bx-edit"></i> Update
                                         </a>
                                     </td>

                            --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Update Delivery Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.order.delivery.update') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <p><strong>Book ID:</strong> <span id="book_id_edit" class=""></span></p>
                        </div>
                        <div class="mb-3">
                            <p><strong>Customer:</strong> <span id="customer_name_edit" class=""></span></p>
                        </div>
                        <input type="hidden" name="user_id" id="userId" value="{{ Session::get('phone') }}">
                        <input type="hidden" name="booking_ref_no" id="booking_ref_no" value="">
                        <div class="mb-3">
                            <label for="delivery_status" class="form-label ">Delivery Status</label>
                            <select class="form-select" name="delivery_status" id="delivery_status" required>
                                <option value="Y">Delivered</option>
                                <option value="N">Pending</option>
                            </select>
                        </div>
                        <div class="mb-3" style="display: none;" id="delivery_date">
                            <label for="update_date" class="form-label ">Delivery Date</label>
                            <input class="form-control" type="date" name="update_date" id="update_date" required>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide success alert
            var successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.transition = 'opacity 0.5s ease-out';
                    successAlert.style.opacity = '0';
                    setTimeout(function() {
                        successAlert.remove();
                    }, 500);
                }, 5000);
            }

            // Initialize DataTables after the DOM is fully loaded
            $(document).ready(function() {
                $('#tblUser').DataTable();
                $('#itemsTable').DataTable();
            });
        });

        $(document).ready(function() {

            // Price calculation
            $('.price_btn').on('click', function() {
                const bookingId = $(this).data('ref_id');
                const loaderGif = $(`#${bookingId}_loader_gif`);
                const priceTab = $(`#${bookingId}_price_tab`);
                const totalPriceElement = $(`#${bookingId}_total_price`);

                loaderGif.show();
                priceTab.hide();

                $.ajax({
                    url: "{{ route('order.pricing') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        booking_id: bookingId
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            totalPriceElement.text(response.total_price);
                            loaderGif.hide();
                            priceTab.show();
                        } else {
                            totalPriceElement.text('Error!');
                            loaderGif.hide();
                            priceTab.show();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", xhr, status, error);
                        totalPriceElement.text('Error!');
                        loaderGif.hide();
                        priceTab.show();
                        // Optionally, display a user-friendly error message:
                        alert(
                            "An error occurred while calculating the price. Please try again."
                        );
                    }
                });
            });

            // Edit Modal
            $('#editModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const bookingID = button.data('booking-id');
                const customerName = button.data('customer-name');
                const deliveryStatus = button.data('delivery-status');
                const deliveryAt = button.data('delivery-at');

                const modal = $(this);
                modal.find('#book_id_edit').text(bookingID);
                modal.find('#customer_name_edit').text(customerName);
                modal.find('#booking_ref_no').val(bookingID);
                modal.find('#delivery_status').val(deliveryStatus);
                modal.find('#update_date').val(deliveryAt);

                if (deliveryStatus === 'Y') {
                    $('#delivery_date').show();
                } else {
                    $('#delivery_date').hide();
                }
            });

            $('#delivery_status').on('change', function() {
                if ($(this).val() === 'Y') {
                    $('#delivery_date').show();
                } else {
                    $('#delivery_date').hide();
                }
            });
        });
    </script>
@endsection
