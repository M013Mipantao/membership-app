@extends('layouts.single_page_ui')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-12 text-center">
                <h1 class="display-1 text-danger">404</h1>
                <p class="lead">QR Code Not Found</p>
                <p>The qr requested is unavailable or not found in our system.</p>
                <a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a>
            </div>
        </div>
    </div>
@endsection

