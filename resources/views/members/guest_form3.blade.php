
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

@include('partials.navbar_singlepage')
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
    <div class="container" id="show-here">
        <!-- Row 1 -->
        <div class="row">
            <!-- Membership ID -->
            @if (Auth::check())
            <div class="col-md-6 d-none">
                <label for="guest-member">Membership ID:</label>
                <input type="text" name="fk_member_guest_id" class="form-control" value="{{ session('member')->id }}" readonly>
            </div>
            <div class="col-md-6">
                <label for="dis_guest-member">Membership ID:</label>
                <input type="text" name="dis_fk_member_guest_id" class="form-control" value="{{ $member_id }}" readonly>
            </div>
            @else
            <div class="col-md-6">
                <label for="guest-member">Membership ID:</label>
                <select id="guest-member" class="form-control" name="fk_member_guest_id" required>
                    <option value="">Membership ID</option>
                    @foreach($members as $member)
                    <option value="{{ $member->id }}">{{ $member->membership_id }} ({{ $member->members_name }})</option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Guest Name -->
            <div class="col-md-6">
                <label for="guests_name">Guest Name:</label>
                <input id="guests_name" type="text" name="guests_name" class="form-control" placeholder="Guest Name" required>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="row mt-3">
            
            <!-- Guest Email -->
            <div class="col-md-6">
                <label for="guests_email">Guest Email:</label>
                <input type="email" name="guests_email" class="form-control" placeholder="Guest Email" required>
            </div>

            <!-- Contact -->
            <div class="col-md-6">
                <label for="contact">Contact Number:</label>
                <input type="text" name="contact" class="form-control" placeholder="Contact Number" required>
            </div>
        </div>
        <div class="divider mt-4" >
            <span>Visit Date</span>
            <div class="row">
            <!-- Buttons for 2 days, 1 week, and 1 month -->
            {{-- <div class="mt-2 button-group">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-2-days">2 Days</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-1-week">1 Week</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-1-month">1 Month</button>
            </div> --}}
            <div class="col-md-6">
                <label for="startdate">Start Date</label>
                <input type="date" id="startdate" name="startdate" class="form-control" required>
            </div>
    
            <div class="col-md-6">
                <label for="enddate">End Date</label>
                <input type="date" id="enddate" name="enddate" class="form-control">
            </div>
            </div>
 
            <!-- Status -->
            <div class="form-group d-none">
                <label for="status">Status:</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
        </div>
        <!-- Agreement Checkbox -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="agreementCheckbox" required disabled>
                    <label class="custom-control-label" for="agreementCheckbox">
                        I agree to the <a href="#" id="termsLink" onclick="displayGuestName()">Consent Form</a>.
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row mt-4">
            <div class="col-md-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</form>

<!-- Terms and Conditions Modal -->
<div id="termsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h5>Consent Form</h5>
            <p class="text-gray-700">
                I, 
                <strong>{{ $data['member']->members_name }}</strong>
                , hereby give permission for the guest, 
                <strong id="displayArea"></strong>
                , to use my account balance.
            </p>
        <button id="agreeTermsBtn" class="btn btn-success">I Agree</button>
    </div>
</div>


<script>
    function displayGuestName(){
        var guestName = document.getElementById("guests_name").value;
        document.getElementById("displayArea").innerText = guestName;
    }
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


