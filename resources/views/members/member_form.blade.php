<form action="{{ route('members.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="membership_id">Member ID:</label>
        <input type="text" id="membership_id" name="membership_id" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="members_name">Member Name:</label>
        <input type="text" id="members_name" name="members_name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="members_email">Email:</label>
        <input type="email" id="members_email" name="members_email" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="gender">Gender:</label>
        <select id="gender" name="gender" class="form-control" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
    </div>
    <div class="form-group">
        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="status">Status:</label>
        <select id="status" name="status" class="form-control" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
</form>