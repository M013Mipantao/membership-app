
@if(Auth::check())
    {{-- <h1>User Profile</h1>
    <p>Name: {{ Auth::user()->name }}</p>
    <p>Email: {{ Auth::user()->email }}</p> --}}

    @if(session('member'))
        {{-- <h2>Member Information</h2>
        <p>Membership ID: {{ session('member')->membership_id }}</p>
        <p>Status: {{ session('member')->status }}</p> --}}
        <?php $member_name =  Auth::user()->name  ?>  

    @endif
@else
    <p>Please log in to view your profile.</p>
@endif

<div class="card card-waves mb-4 mt-5">
    <div class="card-body p-5">
        <div class="row align-items-center justify-content-between">
            <div class="col">
                <h2 class="text-primary">Welcome, @if(Auth::check()) {{  $member_name  }} @endif ! </h2>
                <p class="text-gray-700">Great job, your affiliate dashboard is ready to go! You can view your guest activities and allow them to used your consumable balance.</p>
                <a class="btn btn-primary p-3">
                    Get Started
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right ms-1">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
            <div class="col d-none d-lg-block mt-xxl-n4">
                <img class="img-fluid px-xl-4 mt-xxl-n5" src="assets/img/illustrations/statistics.svg" alt="Dashboard Illustration">
            </div>
        </div>
    </div>
    <!-- Wave SVG -->
    <div class="card-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="#4e73df" fill-opacity="0.1" d="M0,224L48,208C96,192,192,160,288,144C384,128,480,128,576,138.7C672,149,768,171,864,176C960,181,1056,171,1152,149.3C1248,128,1344,96,1392,80L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</div>
