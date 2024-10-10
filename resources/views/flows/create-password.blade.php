@extends('layouts.single_page_ui') <!-- Assuming SB Admin 2 layout -->

@section('content-login')
<div class="container-fluid">
    @if ($errors->any())
    <div class="row">
        <div id="floating-alert" class="alert alert-danger col-12 col-md-8 col-lg-6 mx-auto mt-4" style="position: fixed; z-index: 9999; top: 20px; left: 50%; transform: translateX(-50%);">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif


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
                        <div class="card o-hidden border-0 shadow-lg">
                            <div class="card-body p-5">
                               
                                <div class="text-left">
                                    <h1 class="h4 text-gray-900">Create Password</h1>
                                </div>
                                <form method="POST" action="{{ route('password.store') }}">
                                    @csrf
                            
                                    <input type="hidden" name="membership_id" value="{{ $membership_id }}">

                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input class="form-control form-control-user" type="password" id="password" name="password" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input class="form-control form-control-user" type="password" id="password_confirmation" name="password_confirmation" required>
                                    </div>

                                    <small class="form-text" id="passwordHelp">
                                        <span id="minLength" class="text-muted">- Minimum 8 characters</span><br>
                                        <span id="matchPassword" class="text-muted">- Must match the 'password confirmation' field</span><br>
                                        <span id="lowercase" class="text-muted">- Must contain at least one lowercase letter</span><br>
                                        <span id="uppercase" class="text-muted">- Must contain at least one uppercase letter</span><br>
                                        <span id="number" class="text-muted">- Must contain at least one number</span><br>
                                        <span id="specialChar" class="text-muted">- Must contain at least one special character (e.g. @, $, !, %, *, #, ? or &)</span>
                                    </small>

                                    <button type="submit" class="btn btn-primary btn-user btn-block d-flex justify-content-center mt-4">Create Password</button>
                                </form>

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

<script>

    // Automatically remove the alert after 5 seconds
    setTimeout(function() {
        var alertBox = document.getElementById('floating-alert');
        if (alertBox) {
            alertBox.style.display = 'none';
        }
    }, 5000); // 5000ms = 5 seconds

    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');

        const minLength = document.getElementById('minLength');
        const matchPassword = document.getElementById('matchPassword');
        const lowercase = document.getElementById('lowercase');
        const uppercase = document.getElementById('uppercase');
        const number = document.getElementById('number');
        const specialChar = document.getElementById('specialChar');

        password.addEventListener('input', validatePassword);
        passwordConfirmation.addEventListener('input', validatePassword);

        function validatePassword() {
            const passwordValue = password.value;
            const passwordConfirmValue = passwordConfirmation.value;

            // Minimum length check
            if (passwordValue.length >= 8) {
                minLength.classList.remove('text-muted');
                minLength.classList.add('text-success');
            } else {
                minLength.classList.remove('text-success');
                minLength.classList.add('text-muted');
            }

            // Match password confirmation check
            if (passwordValue === passwordConfirmValue && passwordConfirmValue !== '') {
                matchPassword.classList.remove('text-muted');
                matchPassword.classList.add('text-success');
            } else {
                matchPassword.classList.remove('text-success');
                matchPassword.classList.add('text-muted');
            }

            // Lowercase letter check
            if (/[a-z]/.test(passwordValue)) {
                lowercase.classList.remove('text-muted');
                lowercase.classList.add('text-success');
            } else {
                lowercase.classList.remove('text-success');
                lowercase.classList.add('text-muted');
            }

            // Uppercase letter check
            if (/[A-Z]/.test(passwordValue)) {
                uppercase.classList.remove('text-muted');
                uppercase.classList.add('text-success');
            } else {
                uppercase.classList.remove('text-success');
                uppercase.classList.add('text-muted');
            }

            // Number check
            if (/[0-9]/.test(passwordValue)) {
                number.classList.remove('text-muted');
                number.classList.add('text-success');
            } else {
                number.classList.remove('text-success');
                number.classList.add('text-muted');
            }

            // Special character check
            if (/[@$!%*#?&]/.test(passwordValue)) {
                specialChar.classList.remove('text-muted');
                specialChar.classList.add('text-success');
            } else {
                specialChar.classList.remove('text-success');
                specialChar.classList.add('text-muted');
            }
        }
    });
</script>
@endsection
