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
        <div class="card-body">
            <form action="{{ route('admin.order.history') }}" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-12 col-md-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input class="form-control" type="date" name="start_date" value="{{ $first }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input class="form-control" type="date" name="end_date" value="{{ $today }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="submit" class="btn btn-primary">View History</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card my-2">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h5>Order History
                <br>
                <span class="text-sm text-secondary">History of all the items ordered between <span class="text-primary">
                        {{ $first }}</span> to
                    <span class="text-primary">{{ $today }}</span>.</span>
            </h5>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                console.log(customerName);

                // var email = button.data('email');
                // var address = button.data('address');
                // var roles = button.data('roles').split(',').map(role => role.trim());

                var modal = $(this);
                modal.find('#book_id_edit').text(bookingID);
                modal.find('#customer_name_edit').text(customerName);
                // modal.find('#modalEmail').text(email);
                // modal.find('#modalAddress').text(address);

                // // Ensure Role F is always checked
                // modal.find('input[type="checkbox"]').prop('checked', false);
                // modal.find('input[type="checkbox"][disabled]').prop('checked', true);

                // roles.forEach(function(role) {
                //     var checkbox = modal.find('#role_' + role);
                //     if (checkbox.length) {
                //         checkbox.prop('checked', true);
                //     }
                // });
            });
        });
    </script>
@endsection
