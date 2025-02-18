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
    @if ($message = Session::get('error'))
        <div id="errorAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row justify-content-between">
        <div class="col-12 col-md-5 mt-1">
            <div class="card">
                <div class="card-header lh-1">
                    <h5 class="text-md lh-1">Place order for customer
                        <br>
                        <span class="text-xs text-secondary">
                            Search customer by name and the phone number.
                        </span>
                    </h5>
                </div>
                <div class="card-body lh-1">
                    <form action="" id="customerForm" method="POST">
                        @csrf
                        <div class="row align-items-center text-sm">
                            <div class="col-12 mb-1">
                                <select class="form-select form-select-sm" id="customer" name="customer">
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->phone_no }}" data-name="{{ $customer->full_name }}"
                                            {{ old('customer') == $customer->phone_no ? 'selected' : '' }}>
                                            {{ $customer->full_name }} - {{ $customer->phone_no }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 my-1">
                                {{-- <a href="{{ route('admin.user.order') }}">
                                    <button type="button" id="searchUser" class="btn btn-sm btn-primary">View All Orders
                                    </button>
                                </a> --}}
                                <p id="loader_gif" style="display:none;">
                                    <img src="{{ asset('admin_assets\img\gif\loader.gif') }}" alt="loader" width="30px;">
                                    Getting user details
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-7 mt-1">
            <div class="card" id="user_info_container" style="display: none;">
                <div class="card-header">
                    <h5 class="text-md lh-1">User Details
                        <br>
                        <span class="text-xs text-info">Successfully fetching user information.</span>
                    </h5>
                </div>
                <div class="card-body row px-4 pb-2 text-sm">
                    <div class="col-3">Name</div>
                    <div class="col-9 text-secondary"><span>: </span><span id="user_name"></span></div>
                    <div class="col-3">Mobile</div>
                    <div class="col-9 text-secondary"><span>: </span><span id="user_phone"></span></div>
                    <div class="col-3">Address 1</div>
                    <div class="col-9 text-secondary"><span>: </span><span id="user_add1"></span></div>
                    <div class="col-3">Address 2</div>
                    <div class="col-9 text-secondary"><span>: </span><span id="user_add2"></span></div>
                    <div class="col-3">PIN</div>
                    <div class="col-9 text-secondary"><span>: </span><span id="user_pin"></span></div>
                    <div class="my-2">
                        <form action="{{ route('admin.place.order') }}" method="GET">
                            @csrf
                            <input type="hidden" id="input_user_name" name="name" value="">
                            <input type="hidden" id="input_user_phone" name="phone" value="">
                            <input type="hidden" id="input_pin_code" name="pin" value="">
                            <input type="hidden" id="input_addr_code" name="address" value="">
                            <button type="submit" class="btn btn-sm btn-primary">Continue Shopping</button>
                        </form>
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

            var errorAlert = document.getElementById('errorAlert');

            if (errorAlert) {
                setTimeout(function() {
                    errorAlert.style.opacity = '0';
                    errorAlert.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(function() {
                        errorAlert.remove();
                    }, 500);
                }, 5000);
            }
        });

        $(document).ready(function() {
            $('#tblUser').DataTable();
            $("#customer").select2();
            $(document).on('select2:open', function(e) {
                document.querySelector(`[aria-controls="select2-${e.target.id}-results"]`).focus();
            });

            // get customer address 
            $('#customer').on('change', function(event) {
                event.preventDefault(); // Prevent the default form submission
                $("#loader_gif").show();
                var customerPhone = $('#customer').val();
                var customerName = $('#customer').find(':selected').data('name');
                $.ajax({
                    url: "{{ route('admin.customer.address') }}", // Laravel route URL
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}", // Include the CSRF token
                        customer: customerPhone
                    },
                    success: function(response) {
                        // Handle the successful response here
                        console.log(response.addresses);
                        if (response.status == true) {
                            $('#user_name').text(customerName);
                            $('#input_user_name').val(customerName);
                            $('#user_phone').text(customerPhone);
                            $('#input_user_phone').val(customerPhone);
                            $('#user_pin').text(response.addresses[0]['pin_code']);
                            $('#input_pin_code').val(response.addresses[0]['pin_code']);
                            $('#input_addr_code').val(response.addresses[0]['address_cd']);
                            $('#user_add1').text(response.addresses[0]['add_line1']);
                            $('#user_add2').text(response.addresses[0]['add_line2']);
                            $("#loader_gif").hide();
                            $("#user_info_container").show();
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors here
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
