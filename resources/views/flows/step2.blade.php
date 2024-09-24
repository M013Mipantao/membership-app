@extends('layouts.single_page_ui')
@section('steps')
    @include('flows.steps')
@endsection
@section('content')
    <div class="container mt-5">
        <h3 class="text-primary">Step 2</h3>
        <h5 class="card-title mb-4"> Allowing to Access your Fund</h5>
        <div class="card-body">
            @include('members.guest_form2')
        </div>
    </div>
@endsection
