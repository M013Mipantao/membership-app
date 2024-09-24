@extends('layouts.index')

@section('content')
    <!-- Begin Page Content -->
    {{-- <div class="container-xl px-4 mt-4"> --}}
        <div class="row">
       
            <!-- Success Message -->
            @if (session('success'))
                <div class="col-12">
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="col-12">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Profile Picture and Form Section -->
            <div class="col-xl-4 col-lg-6">
                <!-- Profile picture card-->
                <div class="card shadow mb-4">
                    <div class="card-header">Profile Picture</div>
                    <div class="card-body text-center">
                        <!-- Profile picture image-->
                        <img class="img-account-profile rounded-circle mb-2" src="{{ asset('assets/img/illustrations/profiles/profile-1.png') }}" alt="Profile Picture">
                        <!-- Profile picture help block-->
                        <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                        <!-- Profile picture upload button-->
                        <button class="btn btn-primary" type="button">Upload new image</button>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-6">
                <!-- Member Details Card -->
                <div class="card mb-4">
                    <div class="card-header">Member Details</div>
                    <div class="card-body"> 
                        @include('members.member_form')
                    </div>
                </div>
            </div>
        </div>
    {{-- </div> --}}
    <!-- /.container-fluid -->
@endsection
