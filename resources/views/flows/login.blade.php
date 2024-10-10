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
                                    <h1 id="title" class="h4 text-gray-900 mb-4">Welcome!</h1>
                                </div>
                                <div class="text-left">
                                    <h4 id="title-pass" class="text-gray-900 mb-4 d-none">Enter your password</h4>
                                </div>

                                <!-- Login Form -->
                                <form class="user" id="login-form" method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <!-- Membership ID Section -->
                                    <div class="form-group" id="membership-section">
                                        <input type="text" class="form-control form-control-user" id="membership_id" name="membership_id" placeholder="Enter your Membership ID" required>
                                        <small class="text-danger d-none" id="membership-error"></small>
                                    </div>

                                    <!-- Password Section (Initially hidden) -->
                                    <div class="form-group d-none" id="password-section">
                                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required>
                                        <small class="text-danger d-none" id="password-error"></small>
                                    </div>

                                    <!-- Buttons -->
                                    <button type="button" class="btn btn-primary btn-user btn-block d-flex justify-content-center" id="next-button">
                                        Next
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-user btn-block d-none" id="login-button">
                                        Login
                                    </button>
                                    
                                </form>

                                <!-- Forgot Password Link -->
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  document.getElementById('next-button').addEventListener('click', function() {
    // Create a FormData object with the membership ID
    let formData = new FormData(document.getElementById('login-form'));

    // AJAX request to validate membership ID
    fetch('{{ route('validateMembership') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.first_time) {
                // If first-time login, redirect to create password form
                window.location.href = `{{ url('password/create') }}/${formData.get('membership_id')}`;
            } else {
                // Show password field and hide the Next button
                document.getElementById('membership-section').classList.add('d-none');
         
                document.getElementById('password-section').classList.remove('d-none');
                document.getElementById('next-button').classList.add('d-none');
                document.getElementById('login-button').classList.remove('d-none');
                // Remove the Next button entirely from the DOM
                document.getElementById('next-button').remove();
                 // Show the Login button and add necessary classes
                const loginButton = document.getElementById('login-button');
                loginButton.classList.remove('d-none');
                loginButton.classList.add('d-flex', 'justify-content-center');

                const title = document.getElementById('title-pass');
                title.classList.remove('d-none');
                document.getElementById('title').classList.add('d-none');

            }
        } else {
            // Show error message for invalid membership ID
            document.getElementById('membership-error').classList.remove('d-none');
            document.getElementById('membership-error').innerText = data.message;
        }
    })
    .catch(error => console.error('Error:', error));
});

</script>
@endsection
