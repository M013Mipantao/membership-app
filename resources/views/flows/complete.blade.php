@extends('layouts.single_page_ui')
@include('partials.navbar_singlepage')

@section('steps')
    @include('flows.steps')
@endsection

@section('content')
<div class="container text-center mb-5">
     {{-- <pre>{{ print_r(session()->all()) }}</pre> --}}
    <!-- Step Completed Title -->
    <h1 class="my-5">Success! Your form has been successfully submitted and is now complete.</h1>

    <!-- Animated Completed Icon -->
    <div class="mb-5">
        <i class="fas fa-check-circle" style="font-size: 120px; color: #28a745; animation: pop 0.6s ease;"></i>
    </div>

    <!-- Add More Guests Button with Icon -->
    <a id="addMoreGuestBtn" class="btn btn-primary" href="{{route('flows.guest_info')}}">
         Add More Guests
    </a>
</div>

<!-- Include FontAwesome for Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<!-- Add some basic CSS for animation -->
<style>
    @keyframes pop {
        0% {
            transform: scale(0.5);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>
@endsection
