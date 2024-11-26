@extends('layouts.single_page_ui')
@include('partials.navbar_singlepage')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <!-- Date Range Picker -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard') }}">
                <div class="row">
                    <div class="col-md-5">
                        <input type="date" name="start_date" class="form-control" placeholder="Start Date">
                    </div>
                    <div class="col-md-5">
                        <input type="date" name="end_date" class="form-control" placeholder="End Date">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">Apply</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Transaction</h6>
        </div>
        <div class="card-body">
            @if(empty($dataTable))
                <p class="text-center text-muted">Data not yet available</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataTable as $row)
                            <tr>
                                <td>{{ $row['name'] }}</td>
                                <td>{{ $row['position'] }}</td>
                                <td>{{ $row['office'] }}</td>
                                <td>{{ $row['age'] }}</td>
                                <td>{{ $row['start_date'] }}</td>
                                <td>{{ $row['salary'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
        </div>
        <div class="card-body">
            @if(empty($recentActivity))
                <p class="text-center text-muted">No recent activity available</p>
            @else
                <ul class="list-group">
                    @foreach($recentActivity as $activity)
                        <li class="list-group-item">
                            {{ $activity }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Progress Tracker -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Progress Tracker</h6>
        </div>
        <div class="card-body">
            @if(empty($progressTracker))
                <p class="text-center text-muted">Progress data not yet available</p>
            @else
                @foreach($progressTracker as $tracker)
                    <div class="mb-3">
                        <label>{{ $tracker['name'] }}</label>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $tracker['progress'] }}%;" aria-valuenow="{{ $tracker['progress'] }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $tracker['progress'] }}%
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
