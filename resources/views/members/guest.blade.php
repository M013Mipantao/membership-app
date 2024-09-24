@extends('layouts.index')

@section('content')
        <!-- Begin Page Content -->
        <!-- Success and Error Messages -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <!-- Form for adding a guest -->
            <div class="col-xl-6 col-lg-6">
                <div class="card mb-4 shadow">
                    <div class="card-header">
                        Add New Guest
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/guests') }}" method="POST">
                            @csrf
                            <!-- Membership ID Dropdown -->
                            <div class="form-group">
                                <label for="guest-member">Select Membership ID:</label>
                                <select id="guest-member" class="form-control" name="fk_member_guest_id" style="width: 100%;" required>
                                    <option value="">Select a Membership ID</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}">{{ $member->membership_id }} ({{ $member->members_name }})</option>
                                    @endforeach
                                </select>
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

                            <!-- Gender -->
                            <div class="form-group">
                                <label for="gender">Gender:</label>
                                <input type="text" name="gender" class="form-control" placeholder="Gender" required>
                            </div>

                            <!-- Date of Birth -->
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth:</label>
                                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
                            </div>

                            <!-- Status -->
                            <div class="form-group d-none">
                                <label for="status">Status:</label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Add Guest</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Guest List Table -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        Guest List
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="guest-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>QR</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Guest rows will be inserted here by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    
    <!-- /.container -->
@endsection

