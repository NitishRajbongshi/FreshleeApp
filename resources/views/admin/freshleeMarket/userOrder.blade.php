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
        <h5 class="card-header font-bold"><span class="underline">Default Pickup Address</span></h5>
        <div class="card-body d-flex flex-wrap justify-content-between">
            <div>
                <p class="card-text">
                    Address 1:
                    <span class="text-secondary">
                        {{ $picupAddress->address_line1 }}
                    </span>
                </p>
                <p class="card-text">
                    State:
                    <span class="text-secondary">{{ $picupAddress->state_cd == 'AS' ? 'Assam' : 'NA' }}
                    </span>
                </p>
            </div>
            <div>
                <p class="card-text">
                    Address 2:
                    <span class="text-secondary">{{ $picupAddress->address_line2 }}
                    </span>
                </p>
                <p class="card-text">
                    State:
                    <span class="text-secondary">{{ $picupAddress->district_cd == 'KM' ? 'Kamrup(Metro)' : 'NA' }}
                    </span>
                </p>
            </div>
        </div>
    </div>
    <div class="card my-2">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h5>User Order Details
                <br>
                <span class="text-sm text-secondary">Listing all the items between <span class="text-primary">
                        {{ $start }}</span> (Monday) to
                    <span class="text-primary">{{ $today }}</span> (Present Day).</span>
            </h5>
            <div class="d-flex align-items-center">
                <div>
                    <a href="#report" class="text-decoration-underline">
                        Weekly Report
                    </a>
                </div>
                <form action="{{ route('admin.order.history') }}" method="POST">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $first }}">
                    <input type="hidden" name="end_date" value="{{ $today }}">
                    <button type="submit" class="btn btn-md btn-outline-none text-danger text-decoration-underline">
                        Order History
                    </button>
                </form>
            </div>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-2">
            <table class="table" id="tblUser">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Customer Name</th>
                        <th>Phone Number</th>
                        <th>Customer Address</th>
                        <th>Ordered Date</th>
                        <th>Item + Quantity</th>
                        <th>Order Status</th>
                        <th style="display: none;">Order Info</th>
                        <th>Delivery Status</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php
                        $serialNumber = 1; // Initialize serial number counter
                    @endphp
                    @foreach ($data as $index => $item)
                        <tr class="text-center">
                            <td>{{ $serialNumber++ }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->full_name }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->phone_no }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->address_line1 }}</td>
                            <td style="overflow-wrap: break-word; white-space: normal;">{{ $item->order_date }}</td>
                            <td style="overflow-wrap: break-word; text-align: left;">
                                <ol>
                                    @php
                                        $ordered_items = json_decode($item->order_items, true);
                                    @endphp
                                    @foreach ($ordered_items as $ordered_item)
                                        <li>{{ $ordered_item['item_name'] }}:
                                            <span style="font-weight: bold;">{{ $ordered_item['item_quantity'] }}</span>
                                            {{ $ordered_item['item_unit'] }}
                                        </li>
                                    @endforeach
                                </ol>
                            </td>
                            <td style="overflow-wrap: break-word; white-space: normal;">
                                {{ $item->is_delivered == 'Y' ? 'Delivered' : 'Pending' }}</td>
                            <td style="display: none; overflow-wrap: break-word; white-space: normal;">
                                {{ $item->booking_ref_no }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#editModal" data-booking-id="{{ $item->booking_ref_no }}"
                                    data-customer-name="{{ $item->full_name }}"
                                    data-delivery-status="{{ $item->is_delivered }}"
                                    data-delivery-at="{{ $item->delivered_at != null ? $item->delivered_at : $today }}">
                                    <i class="tf-icons bx bx-edit"></i> Update
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div id="report">
            <h5 class="card-header">
                Item Report <br>
                <span class="text-sm text-secondary">Listing all the ordered items between <span class="text-primary">
                        {{ $start }}</span> (Monday) to
                    <span class="text-primary">{{ $today }}</span> (Present Day).</span>
            </h5>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-2">
            <table class="table" id="itemsTable">
                <thead>
                    <tr class="text-center">
                        <th scope="col">SL. No.</th>
                        <th scope="col" class="text-start">ITEM NAME</th>
                        <th scope="col">ITEM COUNT</th>
                        <th scope="col">ITEM UNIT</th>
                    </tr>
                </thead>
                <tbody class="">
                    @php
                        $serialNumber = 1;
                    @endphp
                    @foreach ($itemCounts as $item)
                        <tr class="text-center">
                            <td>{{ $serialNumber++ }}</td>
                            <td class="text-start">{{ $item->item_name }}</td>
                            <td>{{ $item->total_quantity }}</td>
                            <td>{{ $item->unit_min_order_qty }}</td>
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
                            <p><strong>Book ID:</strong> <span id="book_id_edit" class="fw-bold"></span></p>
                        </div>
                        <div class="mb-3">
                            <p><strong>Customer:</strong> <span id="customer_name_edit" class="fw-bold"></span></p>
                        </div>
                        <input type="hidden" name="user_id" id="userId" value="{{ $user->id }}">
                        <input type="hidden" name="booking_ref_no" id="booking_ref_no" value="">
                        <div class="mb-3">
                            <label for="delivery_status" class="form-label fw-bold">Delivery Status</label>
                            <select class="form-select" name="delivery_status" id="delivery_status" required>
                                <option value="Y">Delivered</option>
                                <option value="N">Pending</option>
                            </select>
                        </div>
                        <div class="mb-3" style="display: none;" id="delivery_date">
                            <label for="update_date" class="form-label fw-bold">Delivery Date</label>
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
            $('#itemsTable').DataTable();

            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var bookingID = button.data('booking-id');
                var customerName = button.data('customer-name');
                var deliveryStatus = button.data('delivery-status');
                var deliveryAt = button.data('delivery-at');
                console.log(deliveryAt);
                var modal = $(this);
                modal.find('#book_id_edit').text(bookingID);
                modal.find('#customer_name_edit').text(customerName);
                modal.find('#booking_ref_no').val(bookingID);
                modal.find('#delivery_status').val(deliveryStatus);
                modal.find('#update_date').val(deliveryAt);
                if (deliveryStatus == 'Y') {
                    $('#delivery_date').show();
                } else {
                    $('#delivery_date').hide();
                }
            });

            $('#delivery_status').on('change', function() {
                var deliveryStatus = $(this).val();
                if (deliveryStatus == 'Y') {
                    $('#delivery_date').show();
                } else {
                    $('#delivery_date').hide();
                }
            });
        });
    </script>
@endsection
