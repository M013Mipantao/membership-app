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
