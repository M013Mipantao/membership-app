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
@section('content')
    <div class="container mt-5" name="">
            {{-- <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Step 1: Account Setup</h6>
            </div> --}}
            
            <h3 class="text-primary">Step 1</h3>
            <h5 class="card-title mb-4">Add your Guest Information</h5>
            <div class="card-body">
                @include('members.guest_form')
            </div>
        </div>
    </div>
@endsection
