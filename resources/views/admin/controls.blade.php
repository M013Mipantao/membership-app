@extends('layouts.index')

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Card 1: Add Member -->
        <div class="col-md-4 col-12 mb-3">
            <div class="card text-center shadow d-none d-md-block">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-user-plus text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="card-title">Add Member</h6>
                    <p class="card-text">Register a new member to the system.</p>
                    <a href="{{ route('admin.addMember') }}" class="btn btn-primary btn-sm">Add Member</a>
                </div>
            </div>
        </div>
        <!-- Card 2: Add Guest -->
        <div class="col-md-4 col-12 mb-3">
            <div class="card text-center shadow d-none d-md-block">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-user-friends text-success" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="card-title">Add Guest</h6>
                    <p class="card-text">Register a guest for an event or visit.</p>
                    <a href="{{ route('admin.addGuest') }}" class="btn btn-success btn-sm">Add Guest</a>
                </div>
            </div>
        </div>
        <!-- Card 3: Forget Password -->
        <div class="col-md-4 col-12 mb-3">
            <div class="card text-center shadow d-none d-md-block">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-key text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="card-title">Forget Password</h6>
                    <p class="card-text">Reset your password securely.</p>
                    <a href="{{ route('admin.forgetPassword') }}" class="btn btn-warning btn-sm">Reset Password</a>
                </div>
            </div>
        </div>
        <!-- Card 4: Resend QR Code -->
        <div class="col-md-4 col-12 mb-3">
            <div class="card text-center shadow d-none d-md-block">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-qrcode text-info" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="card-title">Resend QR Code</h6>
                    <p class="card-text">Resend QR codes to users.</p>
                    <a href="{{ route('admin.resendQrCode') }}" class="btn btn-info btn-sm">Resend QR Code</a>
                </div>
            </div>
        </div>
        <!-- Card 5: Help -->
        <div class="col-md-4 col-12 mb-3">
            <div class="card text-center shadow d-none d-md-block">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-question-circle text-danger" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="card-title">Help</h6>
                    <p class="card-text">Access help and support resources.</p>
                    <a href="{{ route('admin.help') }}" class="btn btn-danger btn-sm">Get Help</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any JavaScript functionality here if needed
</script>
@endpush
