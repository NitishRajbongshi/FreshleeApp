@extends('admin.common.layout')
@section('title', 'User Role Management')
@section('custom_header')
@endsection
@section('main')
    <div class="row justify-content-between">
        <div class="col-12 col-md-4 mt-1">
            <div class="card">
                <div class="card-header lh-1">
                    <h2 class="text-lg text-primary">
                        <i class='bx bxs-check-circle text-xl'></i>
                        {{ $order_status }}
                    </h2>
                    <p class="my-2">{{ $message }}</p>
                    <p>Booking ID: {{ $booking_id }}</p>
                    <h5 class="text-md lh-1"> Customer Info:
                        <br>
                        <span class="text-sm">{{ $customer_name }}</span><br>
                        <span class="text-xs text-secondary">
                            {{ $customer_phone }}
                        </span>
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 mt-1">
            <div class="card" id="user_info_container">
                <div class="card-header">
                    <h5 class="text-md lh-1">Ordered Items
                        <br>
                        <span class="text-xs text-secondary">If there is any mistake, modify the item <a
                                href="{{ route('admin.user.order') }}">here</a></span>
                    </h5>
                </div>
                <div class="card-body row px-4 pb-2 text-sm">
                    <ul class="list-group ps-2" style="list-style-type: none;" id="item-list">
                        @foreach ($orderedItems as $item)
                            <li class="list-group-item lh-1">
                                {{ $item['item_name'] }} - Min. Order: {{ $item['item_min_order'] }} :
                                {{ $item['qty'] }} Unit(s)
                            </li>
                        @endforeach
                    </ul>
                    <div class="my-2">
                        <a href="{{ route('admin.proxy.user.list') }}" class="btn btn-sm btn-primary">Place Another
                            Order</a>
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
        });

        $(document).ready(function() {
            //            
        });
    </script>
@endsection
