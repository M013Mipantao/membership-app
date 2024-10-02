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
<form action="{{ route('guest-info-form') }}" method="POST">
    @csrf
    @method('POST')
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
  {{-- <pre>{{ print_r($data) }}</pre> --}}
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
    <div class="form-group mb-4">
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

    {{-- <div class="form-group">
        <label for="visit_type">Visit Type</label>
        <select id="visit_type" name="visit_type" class="form-control" required>
            <option value="one-time">One Time Visit</option>
            <option value="multiple">Multiple Visits</option>
        </select>
    </div> --}}
    <div class="divider">
        <span>Visit Date</span>
 
        <!-- Buttons for 2 days, 1 week, and 1 month -->
        {{-- <div class="mt-2 button-group">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-2-days">2 Days</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-1-week">1 Week</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-1-month">1 Month</button>
        </div> --}}
        <div class="form-group mt-1">
            <label for="startdate">Start Date</label>
            <input type="datetime-local" id="startdate" name="startdate" class="form-control" required>
        </div>

        <div class="form-group mt-3" id="enddate-container" >
            <label for="enddate">End Date</label>
            <input type="datetime-local" id="enddate" name="enddate" class="form-control">
        </div>
    </div>

     <!-- Agreement Checkbox (disabled by default) -->
     <div class="form-group mb-3">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="agreementCheckbox" required disabled>
            <label class="custom-control-label" for="agreementCheckbox">
                I agree to the 
                     <a href="#" id="termsLink">terms and conditions</a>.
                
            </label>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        {{-- <a class="btn btn-light d-none" href="{{ route('flows.step2') }}">Previous</a> --}}
        <button type="submit" class="btn btn-primary">Submit</button>   
        {{-- <a class="btn btn-primary" href="{{ route('flows.step2') }}">Next Step</a> --}}
    </div>
    <!-- Submit Button -->
</form>


    <!-- Terms and Conditions Modal -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h5>Terms and Conditions</h5>
                <p class="text-gray-700">
                    I, 
                    <strong>{{ $data['member']->members_name }}</strong>
                    , hereby give permission for the guest, 
                    <strong>{{ old('guests_name') }}</strong>
                    , to use my account balance and consumable services.
                </p>
            <button id="agreeTermsBtn" class="btn btn-success">I Agree</button>
        </div>
    </div>


<script>
    // function setDateRange(daysToAdd) {
    //     const startDateInput = document.getElementById('startdate');
    //     const endDateInput = document.getElementById('enddate');

    //     const startDate = startDateInput.value ? new Date(startDateInput.value) : new Date();

    //     startDateInput.value = startDate.toISOString().slice(0, 16);

    //     const endDate = new Date(startDate);
    //     endDate.setDate(endDate.getDate() + daysToAdd);
    //     endDateInput.value = endDate.toISOString().slice(0, 16);
    // }

    // document.getElementById('btn-2-days').addEventListener('click', function() {
    //     setDateRange(2);
    // });

    // document.getElementById('btn-1-week').addEventListener('click', function() {
    //     setDateRange(7);
    // });

    // document.getElementById('btn-1-month').addEventListener('click', function() {
    //     setDateRange(30);
    // });

</script>


