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
.divider {
    display: block;
    padding: 20px;
    border: 1px solid #ccc; /* Border around the section */
    border-radius: 10px; /* Rounded corners */
    position: relative;
    margin-bottom: 20px;
    
}

.divider span {
    position: absolute;
    top: -12px; /* Adjusts the position of the label */
    left: 20px; /* Aligns the span to the left */
    background-color: white; /* To match the background of the container */
    padding: 0 10px;
    color: #888;
    font-weight: bold;
    font-size: 1.1rem;
    border-radius: 5px; /* Gives the span rounded corners */
}

.button-group {
    margin-top: 20px;
}

.form-group {
    margin-top: 15px;
}

    /* Modal background */
    .modal {
        display: none; /* Hidden by default */
        position: fixed;
        z-index: 999; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
    }

    /* Modal content box */
    .modal-content {
        background-color: #fff;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px; /* Max width of the modal */
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        position: relative;
    }

    /* Close button (X) */
    .close {
        position: absolute;
        right: 20px;
        top: 10px;
        font-size: 28px;
        font-weight: bold;
        color: #aaa;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #000;
    }

    /* Disabled button styles */
    .btn[disabled] {
        background-color: #ccc;
        border-color: #ccc;
        cursor: not-allowed;
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
    @stack('scripts')
</body>
</html>
