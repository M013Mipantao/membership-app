@extends('layouts.single_page_ui')
@include('partials.navbar_singlepage')

@section('content')
<style>
/* General styling for cards */
.card .btn {
    font-size: 0.85rem;
    padding: 0.4rem 0.8rem;
}
.card .card-text {
    font-size: 0.9rem;
}
.card {
    border-radius: 10px;
}

/* Balance card styling */
.balance-card {
    background: linear-gradient(135deg, #4e73df, #224abe);
    color: #fff;
    text-align: center;
    padding: 50px 20px;
    border-radius: 10px; /* Slight border-radius for rounded corners */
    height: auto; /* Let the card adjust its height based on content */
    min-height: 250px; /* Minimum height to keep it visually balanced */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.balance-card .balance-title {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 20px;
}

.balance-card .balance-amount {
    font-size: 3.5rem;
    font-weight: bold;
    margin-bottom: 20px;
}

.balance-card .balance-footer {
    font-size: 1rem;
    opacity: 0.9;
}

/* Mobile-specific layout */
@media (max-width: 768px) {
    .card {
        display: none; /* Hide cards on mobile */
    }

    .mobile-icons {
        display: flex; /* Display icons in a row */
        justify-content: space-around;
        align-items: center;
        margin-top: 2rem;
    }

    .mobile-icons .icon-wrapper {
        text-align: center;
        flex: 1; /* Distribute icons evenly */
    }

    .mobile-icons .icon-wrapper i {
        font-size: 2.5rem;
    }

    .mobile-icons .icon-wrapper h6 {
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    /* Balance */

    .balance-card {
        padding: 30px 15px; /* Adjust padding for smaller screens */
    }

    .balance-card .balance-title {
        font-size: 1.2rem; /* Smaller title text */
    }

    .balance-card .balance-amount {
        font-size: 2.5rem; /* Smaller amount text */
    }

    .balance-card .balance-footer {
        font-size: 0.8rem; /* Smaller footer text */
    }
}
</style>

<div class="balance-card">
    <div class="balance-title">Current Balance</div>
    {{-- <pre>{{print_r(session()->all())}}</pre> --}}
    <div class="balance-amount">₱ {{ number_format((float)(session('api'))[0]['walBal'], 2, '.', '') }}</div>
    <div class="balance-footer">Membership ID: {{ print_r(session('member')->membership_id)}}</div>
</div>

<div class="container mt-5">
    <div class="row">
        <!-- Card 1 -->
        <div class="col-md-4 col-12 mb-3">
            <div class="card text-center shadow d-none d-md-block">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-wallet text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="card-title">Wallet</h6>
                    <p class="card-text">Check your balance and transactions.</p>
                    <a href="{{route('transactions-tab')}}" class="btn btn-primary btn-sm">Open Wallet</a>
                </div>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="col-md-4 col-12 mb-3">
            <div class="card text-center shadow d-none d-md-block">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-exchange-alt text-success" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="card-title">Authorize</h6>
                    <p class="card-text">Allow access to friends and family.</p>
                    <a href="{{route('flows.guest_info')}}" class="btn btn-success btn-sm">Send Now</a>
                </div>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="col-md-4 col-12 mb-3">
            <div class="card text-center shadow d-none d-md-block">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-qrcode text-info" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="card-title">QR Codes</h6>
                    <p class="card-text">Manage your QR codes easily.</p>
                    <a href="{{route('qr-codes-tab')}}" class="btn btn-info btn-sm">View QR Codes</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Layout -->
    <div class="mobile-icons d-md-none mt-3">
        <a href="{{route('transactions-tab')}}" class="icon-wrapper">
            <i class="fas fa-wallet text-primary"></i>
            <h6>Wallet</h6>
        </a>
        <a href="{{route('flows.guest_info')}}" class="icon-wrapper">
            <i class="fas fa-exchange-alt text-success"></i>
            <h6>Authorize</h6>
        </a>
        <a href="{{route('qr-codes-tab')}}" class="icon-wrapper">
            <i class="fas fa-qrcode text-info"></i>
            <h6>QR Codes</h6>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Custom scripts if needed
</script>
@endpush
