@extends('layouts.single_page_ui')
@include('partials.navbar_singlepage')

@section('content')

<style>
 
    .profile-pic {
        width: 150px;
        height: 150px;
        border-radius: 50%; /* Circular effect */
        object-fit: cover; /* Ensures proper cropping */
        border: 2px solid #ddd; /* Optional: Adds a border */
    }

    

</style>
<div class="container mt-5">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4">
            <div class="card">
                <img 
    src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($member['members_email'] ?? ''))) }}?s=200&d=mp" 
    class="card-img-top" 
    alt="Profile Picture">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $member['members_name'] ?? 'Guest User' }}</h5>
                    <p class="card-text">Member</p>
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="col-md-8">
            <div class="card">
       
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Information</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Email:</strong> {{ $member['members_email'] ?? 'Not Available' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Membership ID:</strong> {{ $member['membership_id'] ?? 'Not Assigned' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Date of Birth:</strong> 
                            {{ \Carbon\Carbon::parse($member['date_of_birth'])->format('F j, Y') ?? 'N/A' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Balance:</strong> {{ (session('api'))[0]['walBal'] }}
                        </li>
                    </ul>
                    <button class="btn btn-primary mt-3">Edit Profile</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Debug Session Data -->

@endsection
