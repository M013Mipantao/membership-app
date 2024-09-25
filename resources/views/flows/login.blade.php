@extends('layouts.single_page_ui') <!-- Assuming SB Admin 2 layout -->
@section('content-login')
<div class="container-fluid">
    <div class="row">
        <!-- Left Side (Full-screen height background image or color) -->
        <div class="col-lg-6 d-none d-lg-block" style="background-image: url('{{ asset('img/login-background.jpg') }}'); background-size: cover; background-position: center; height: 100vh;">
            <!-- You can replace the background image URL with any image in your public/img folder -->
        </div>

        <!-- Right Side (Login Form) -->
        <div class="col-lg-6 d-flex align-items-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card o-hidden border-0 shadow-lg my-5">
                            <div class="card-body p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                </div>

                                <!-- Login Form -->
                                <form class="user" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="membership_id" name="membership_id" placeholder="Enter your Membership ID" required>
                                        @error('membership_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required>
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block d-flex justify-content-center">
                                        Login
                                    </button>                                    
                                </form>

                                <!-- Forgot Password Link -->
                                <hr>
                                <div class="text-center">
                                    {{-- {{ route('password.request') }} --}}
                                    <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                                </div>

                                <!-- Register Link -->
                                {{-- <div class="text-center">
                                    <a class="small" href="{{ route('register') }}">Create an Account!</a>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
