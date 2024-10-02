@extends('layouts.single_page_ui')

<!-- Steps 1 to 4 -->
@section('steps')
    @include('flows.steps')
@endsection
  <!-- Success Message -->
  @if (session('success'))
  <div class="col-12">
      <div class="alert alert-success">
          {{ session('success') }}
      </div>
  </div>
@endif

@section('content')
    <div class="container mt-5" name="">
            {{-- <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Step 1: Account Setup</h6>
            </div> --}}            
            <h3 class="text-primary">Guest Information</h3>
            <h5 class="card-title mb-2">Add your Guest Information</h5>
            <div class="card-body">
                @include('members.guest_form3')
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Get modal and relevant elements
    var modal = document.getElementById("termsModal");
    var termsLink = document.getElementById("termsLink");
    var closeModalSpan = document.getElementsByClassName("close")[0];
    var agreeTermsBtn = document.getElementById("agreeTermsBtn");
    var agreementCheckbox = document.getElementById("agreementCheckbox");
    var submitBtn = document.getElementById("submitBtn");

    // Open the modal when the "terms and conditions" link is clicked
    termsLink.addEventListener("click", function (event) {
        event.preventDefault();  // Prevent the default link behavior
        modal.style.display = "block";  // Show the modal
    });

    // Close the modal when the "X" button is clicked
    closeModalSpan.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Close the modal and enable the checkbox when "I Agree" is clicked
    agreeTermsBtn.addEventListener("click", function () {
        modal.style.display = "none";  // Close the modal
        agreementCheckbox.disabled = false;  // Enable the checkbox
        agreementCheckbox.checked = true;  // Automatically check it
        submitBtn.disabled = false;  // Enable the submit button
    });

    // Close the modal if the user clicks anywhere outside the modal
    window.addEventListener("click", function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
});
</script>
@endpush


