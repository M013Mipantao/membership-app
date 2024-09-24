@if(Auth::check())
    {{-- <h1>User Profile</h1>
    <p>Name: {{ Auth::user()->name }}</p>
    <p>Email: {{ Auth::user()->email }}</p> --}}

    @if(session('member'))
        {{-- <h2>Member Information</h2>
        <p>Membership ID: {{ session('member')->membership_id }}</p>
        <p>Status: {{ session('member')->status }}</p> --}}
        <?php $member_id = session('member')->membership_id; ?>  

    @endif
@else
    <p>Please log in to view your profile.</p>
@endif

<form action="{{ url('/member-guests') }}" method="POST">
    @csrf
    <!-- Membership ID Dropdown -->
  
        @if (Auth::check())
        <div class="form-group d-none">
            <label for="guest-member">Select Membership ID:</label>
            <input type="text" name="fk_member_guest_id" class="form-control" placeholder="Membership ID" value="{{ session('member')->id }}" readonly>
        </div>
        <div class="form-group">
            <label for="dis_guest-member">Select Membership ID:</label>
            <select id="dis_guest-member" class="form-control" name="dis_fk_member_guest_id" style="width: 100%;" disabled>
                <option value="">Select a Membership ID</option>
                    <option value="{{ $member_id }}" selected>{{ $member_id }}</option>
            </select>
        @else
        <select id="guest-member" class="form-control" name="fk_member_guest_id" style="width: 100%;" required>
            <option value="">Select a Membership ID</option>
            @foreach($members as $member)
                <option value="{{ $member->id }}">{{ $member->membership_id }} ({{ $member->members_name }})</option>
            @endforeach
        </select>
        @endif
    </div>

    <!-- Guest Name -->
    <div class="form-group">
        <label for="guests_name">Guest Name:</label>
        <input type="text" name="guests_name" class="form-control" placeholder="Guest Name" required>
    </div>

    <!-- Guest Email -->
    <div class="form-group">
        <label for="guests_email">Guest Email:</label>
        <input type="email" name="guests_email" class="form-control" placeholder="Guest Email" required>
    </div>

    <!-- Contact -->
    <div class="form-group">
        <label for="contact">Contact Number:</label>
        <input type="text" name="contact" class="form-control" placeholder="Contact Number" required>
    </div>

    {{-- <!-- Date of Birth -->
    <div class="form-group">
        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
    </div> --}}

    <!-- Status -->
    <div class="form-group d-none">
        <label for="status">Status:</label>
        <select id="status" name="status" class="form-control" required>
            <option value="Active">Active</option>
            <option value="Inactive" selected>Inactive</option>
        </select>
    </div>
    <div class="d-flex justify-content-between">
        {{-- <a class="btn btn-light d-none" href="{{ route('flows.step2') }}">Previous</a> --}}
        <button type="submit" class="btn btn-primary">Save & Next Step</button>   
        {{-- <a class="btn btn-primary" href="{{ route('flows.step2') }}">Next Step</a> --}}
    </div>
    <!-- Submit Button -->
</form>


@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2 on the membership select element
            $('#guest-member').select2({
                placeholder: 'Search for a Membership ID',
                allowClear: true
            });
        });
    </script>
@endsection