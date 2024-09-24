<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Membership (still no idea with the name)</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">   
    <!-- Custom fonts for this template-->
    <link href="{{ asset('sb-admin-2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('sb-admin-2/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('sb-admin-2/css/wave.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        
        .nav-wizard .nav-link {
            padding: 20px;
            background: #f8f9fc;
            color: #4e73df;
            text-align: center;
        }
        .nav-wizard .nav-link.active {
            background: #fff;
            color: #4e73df;
            font-weight: bold;
            border: 1px solid #e3e6f0;
            border-bottom: none;
        }
        .wizard-step-icon {
            font-size: 1.5rem;
            background-color: #4e73df;
            color: #fff;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            line-height: 35px;
            margin: 0 auto 10px;
        }
        .wizard-step-text {
            text-align: center;
        }
        .wizard-step-text-name {
            font-size: 1rem;
            font-weight: 600;
        }
        .wizard-step-text-details {
            font-size: 0.85rem;
            color: #6e707e;
        }
        .card-title {
            color: #6e707e;
        }

        /* Optional: Customize background or add effects */
        .bg-custom {
            background-color: #f8f9fc; /* Light background */
        }

        .full-height {
            height: 100vh;
        }

    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Steps 1 to 4 -->
        @yield('steps')
        <!-- Page Content -->
        @yield('content')
    </div>

    @yield('content-login')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include the QR Code library -->

</body>
</html>
