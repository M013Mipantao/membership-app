
@extends('layouts.scan_result_ui')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">QR Code Scan Result</h1>

    <!-- Scan Result Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Guest Information</h6>
        </div>
        @php
            print_r($scanResult)
        @endphp        
        <div class="card-body">
            <div class="row">
                <!-- Name -->
                <div class="col-md-4">
                    <h6><strong>Name:</strong></h6>
                    <p>{{ $scanResult['name'] ?? 'Unknown' }}</p>
                </div>

                <!-- Visit Date -->
                <div class="col-md-4">
                    <h6><strong>Visit Date:</strong></h6>
                    <p>{{ $scanResult['visitdate'] ?? 'Unknown' }}</p>
                </div>

                <!-- Status -->
                <div class="col-md-4">
                    <h6><strong>Status:</strong></h6>
                    <p>
                        @if(isset($scanResult['status']) && $scanResult['status'] === 'active')
                            <span class="badge badge-success">{{ $scanResult['status'] }}</span>
                        @else
                            <span class="badge badge-danger">{{ $scanResult['status'] ?? 'Unknown' }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
