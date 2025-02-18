<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('admin_assets') }}" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Freshlee (BETA)</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('admin_assets/img/favicon/logo.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/css/core.css') }}"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('admin_assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/css/pages/page-auth.css') }}" />
    <!-- Helpers -->
    <script src="{{ asset('admin_assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('admin_assets/js/config.js') }}"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Add the blurred background */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('crop.jpg') }}');
            background-size: cover;
            background-position: center;
            filter: blur(3px);
            z-index: -1;
        }

        .login_form {
            background: rgb(255, 255, 255);
            background: linear-gradient(180deg, rgba(255, 255, 255, 1) 23%, rgba(247, 255, 238, 1) 81%, rgba(242, 255, 225, 1) 100%);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 5px 5px 10px #4d4d4d30, -5px -5px 10px #45454530;
            position: absolute;
            top: 50%;
            width: 400px;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
        }

        .login_password_input input {
            width: 100%;
            /* padding: 0 5px; */
            height: 40px;
            font-size: 16px;
            border: none;
            background: none;
            outline: none;
        }

        .login_phone_input label {
            color: #00885a;
            font-weight: 600;
        }

        .login_phone_input {
            margin-top: 30px;
        }

        .login_phone_input #phone,
        .smallText {
            color: #00885a;
        }

        .login_form .form-control {
            background-color: rgba(248, 248, 248, 0.986) !important;
            border: 0.5px solid #00885b6e;
            border-radius: 0.4rem;
            width: 100%;
            box-sizing: border-box;
        }

        .login_button .login_button {
            width: 100%;
            border-radius: 26px;
            font-size: 18px;
            text-align: center;
            margin-top: 16px;
            padding: 6px 26px;
            background-color: #00885a;
            color: #ffffff;
        }


        .login_button .login_button:hover {
            background-color: #026141;
            color: #ffffff;
        }

        .show_password {
            text-align: right;
            color: #00885a;
            font-size: 14px;
        }

        .login_form h5 {
            text-align: center;
            color: #00885a;
            font-size: 1.2rem;
            letter-spacing: 3px;
            font-weight: 600;
            text-transform: capitalize;
        }
    </style>
</head>

<body>
    <div class="login_form">
        <div class="text-center mb-2">
            <img src="{{ asset('admin_assets\img\favicon\logo.png') }}" alt="Freshlee Logo" width="60rem"
                height="auto">
        </div>
        <h5>Welcome to Freshlee</h5>
        @if (session('error'))
            <div id="successAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form id="formAuthentication" class="mb-3" action="{{ route('auth.login') }}" method="POST">
            @csrf
            <div class="login_phone_input">
                <label for="phone" class="form-label">
                    <i class='bx bxs-phone-call'></i>
                    Enter Registered Mobile No. :
                </label>
                <input type="tel" class="form-control" id="phone" name="phone" maxlength="10" minlength="10"
                    pattern="[0-9]{10}" value="{{ old('phone') }}" placeholder="xxxxx xxxxx" autofocus />
                @error('phone')
                    <div class="text-xs text-danger"><i class='bx bxs-info-circle mr-1'></i> {{ $message }}</div>
                @enderror
                <small class="smallText my-2"><i class='bx bxs-info-circle mr-1'></i>An OTP will be sent to your mobile
                    number.</small>
            </div>
            {{-- <div class="login_password_input">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" placeholder="Enter Password" aria-describedby="password" />
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="show_password">
                <input type="checkbox" id="showPasswordCheckbox" onchange="togglePasswordVisibility()">
                Show Password
            </div> --}}
            <div class="login_button">
                <button class="btn login_button" type="submit">Generate OTP</button>
            </div>
        </form>
    </div>
    <script src="{{ asset('admin_assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('admin_assets/js/main.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
    </script>
</body>

</html>
