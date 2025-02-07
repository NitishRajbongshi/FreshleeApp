@extends('admin.common.layout')
@section('title', 'User Role Management')
@section('custom_header')
@endsection
@section('main')
    <div class="card">
        <div class="card-body lh-1">
            <form action="{{ route('admin.order.history') }}" method="POST">
                @csrf
                <div class="row align-items-end text-sm">
                    <div class="col-12 col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input class="form-control form-control-sm" type="date" name="start_date"
                            value="{{ $first }}">
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input class="form-control form-control-sm" type="date" name="end_date"
                            value="{{ $today }}">
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" class="btn btn-sm btn-primary">View History</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card my-1">
        <div class="card-header">
            <h5 class="text-md lh-1">User Order History
                <br>
                <span class="text-xs text-secondary">Showing results between <span class="text-primary">
                        {{ $first }}</span> to
                    <span class="text-primary">{{ $today }}</span>.</span>
            </h5>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-2">
            <table class="table text-xs" id="tblUser">
                <thead>
                    <tr>
                        <th>Customer Info</th>
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
                            <td style="overflow-wrap: break-word; white-space: normal;">
                                <span>{{ $item->full_name }}</span><br>
                                <span>Ph: +91 {{ $item->phone_no }}</span>
                            </td>
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
        });
    </script>
@endsection
