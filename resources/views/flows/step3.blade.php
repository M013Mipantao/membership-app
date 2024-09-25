@extends('layouts.single_page_ui')

@section('steps')
    @include('flows.steps')
@endsection

@section('content')
    <div class="container mt-5">
        <h3 class="text-primary">Step 3</h3>
        <h5 class="card-title mb-4">Review and Submit Changes</h5>
        <form action="{{ route('flows.complete', $data['guest_id']) }}" method="GET">
            <!-- Member Information Section -->
            <div class="card mb-4">
                {{-- <pre>{{ print_r($data) }}</pre> --}}
                <div class="card-body">
                    <h6>Member Information</h6>
                    <p class="text-gray-700">
                        <strong>Name:</strong> {{ $data['member']->members_name }}<br>
                        <strong>Email:</strong> {{ $data['member']->members_email }}<br>
                        <strong>Membership ID:</strong> {{ $data['member']->membership_id }}<br>
                        {{-- <strong>Balance:</strong> {{ $data['member']->balance }} <!-- Assuming balance is available --> --}}
                    </p>
                </div>
            </div>

            <!-- Guest Information Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6>Guest Information</h6>
                    <p class="text-gray-700">
                        <strong>Name:</strong> {{ $data['guest_name'] }}<br>
                        <strong>Email:</strong> {{ $data['guest_email'] ?? 'Not Provided' }}<br> <!-- Assuming guest email might not be provided -->
                        {{-- <strong>QR Code:</strong> {{ session('guest')->qr_code }} <!-- Assuming QR code is available --> --}}
                    </p>
                </div>
            </div>

            <!-- QR Code Field Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6>QR Code</h6>
                    <div class="form-group">
                        <a href="{{ route('download.qr.code', $data['qr_code_id']) }}" download>Download QR Code</a>
                    </div>
                </div>
            </div>

            <!-- Consent Letter Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6>Consent Letter</h6>
                    <p class="text-gray-700">
                        I, <strong>{{ $data['member']->members_name }}</strong>, hereby give permission for the guest, <strong>{{ $data['guest_name'] }}</strong>, to use my account balance and consumable services.
                    </p>
                    
                    @if($data['visit_type'] === 'one-time')
                    <p class="text-gray-700">
                        This consent is for a one-time visit on <strong>{{ \Carbon\Carbon::parse($data['startdate'])->format('F j, Y \a\t h:i A') }}</strong>.
                    </p>
                @elseif($data['visit_type'] === 'multiple')
                    <p class="text-gray-700">
                        This consent is for multiple visits starting from <strong>{{ \Carbon\Carbon::parse($data['startdate'])->format('F j, Y \a\t h:i A') }}</strong> to <strong>{{ \Carbon\Carbon::parse($data['enddate'])->format('F j, Y \a\t h:i A') }}</strong>.
                    </p>
                @endif
                
            
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="consent" required>
                        <label class="form-check-label" for="consent">I agree to the terms.</label>
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="d-flex justify-content-between mb-5">
                <a type="button" class="btn btn-secondary" href="{{route('flows.step2')}}" >Previous</a>
                <button type="submit" class="btn btn-primary" id="send-mail-btn">Send Mail to Complete</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('send-mail-btn').addEventListener('click', function() {
        fetch('/send-guest-email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                qr_code: document.getElementById('qr-code').value // Adjust as needed
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text) });
            }
            return response.json();
        })
        .then(data => {
            alert('Email sent successfully to ' + data.guestEmail);
        })
        .catch(error => {
            console.error('Error sending email:', error);
        });
    });
</script>
@endpush
