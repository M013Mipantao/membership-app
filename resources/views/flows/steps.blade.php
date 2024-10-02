@include('flows.news')
    {{-- <!-- Wizard Navigation -->
    <div class="nav nav-pills nav-justified flex-column flex-xl-row nav-wizard" id="wizardTab" role="tablist">
        <!-- Wizard navigation item 1 -->
        <a class="nav-item nav-link {{ request()->routeIs('flows.step1') ? 'active' : 'disabled' }}" id="wizard1-tab" href="{{ route('flows.step1') }}" role="tab" aria-controls="wizard1" aria-selected="{{ request()->routeIs('flows.step1') ? 'true' : 'false' }}">
            <div class="wizard-step-icon">1</div>
            <div class="wizard-step-text">
                <div class="wizard-step-text-name">Guest Information</div>
                <div class="wizard-step-text-details">Add your guest Information</div>
            </div>
        </a>
        <!-- Wizard navigation item 2 -->
        <a class="nav-item nav-link {{ request()->routeIs('flows.step2') ? 'active' : 'disabled' }}" id="wizard2-tab" href="{{ route('flows.step2') }}" role="tab" aria-controls="wizard2" aria-selected="{{ request()->routeIs('flows.step2') ? 'true' : 'false' }}">
            <div class="wizard-step-icon">2</div>
            <div class="wizard-step-text">
                <div class="wizard-step-text-name">Fund Sharing</div>
                <div class="wizard-step-text-details">Select and Allowing to Access your Fund</div>
            </div>
        </a>
        <!-- Wizard navigation item 3 -->
        <a class="nav-item nav-link {{ request()->routeIs('flows.step3') ? 'active' : 'disabled' }}" id="wizard3-tab" href="{{ route('flows.step3') }}" role="tab" aria-controls="wizard3" aria-selected="{{ request()->routeIs('flows.step3') ? 'true' : 'false' }}">
            <div class="wizard-step-icon">3</div>
            <div class="wizard-step-text">
                <div class="wizard-step-text-name">Review & Submit</div>
                <div class="wizard-step-text-details">Review and submit changes</div>
            </div>
        </a>
       <!-- Wizard navigation item 4 -->
        <a class="nav-item nav-link {{ request()->routeIs('flows.step4') ? 'active' : '' }}" id="wizard4-tab" href="{{ route('flows.step4') }}" role="tab" aria-controls="wizard4" aria-selected="{{ request()->routeIs('flows.step4') ? 'true' : 'false' }}">
            <div class="wizard-step-icon">4</div>
            <div class="wizard-step-text">
                <div class="wizard-step-text-name">Review & Submit</div>
                <div class="wizard-step-text-details">Review and submit changes</div>
            </div>
        </a> 
    </div> --}}
