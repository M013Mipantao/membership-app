@extends('layouts.single_page_ui') <!-- Assuming SB Admin 2 layout -->
@section('styles')
<link href="{{ asset('sb-admin-2/css/otp.css') }}" rel="stylesheet">
@endsection
@section('content') <!-- For additional styles if needed -->

    <h2>Verify Your OTP</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('otp.verify') }}" method="POST">
        @csrf
        <div class="otp-input">
            <label for="otp">Enter OTP:</label>
            <input type="text" id="otp" name="otp" required>
        </div>
        <button type="submit" class="button">Verify OTP</button>
    </form>


@endsection
