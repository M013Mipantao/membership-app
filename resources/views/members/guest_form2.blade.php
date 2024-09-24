<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('update.qr.code', $guest_id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Guest Name -->
        <div class="form-group">
            <label for="guests_name">Guest Name:</label>
            <input type="text" name="guests_name" class="form-control" placeholder="Guest Name" value="{{$guest_name}}" disabled>
        </div>

        <div class="form-group">
            <label for="visit_type">Visit Type</label>
            <select id="visit_type" name="visit_type" class="form-control" required>
                <option value="one-time">One Time Visit</option>
                <option value="multiple">Multiple Visits</option>
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="startdate">Start Date</label>
            <input type="datetime-local" id="startdate" name="startdate" class="form-control" required>
        </div>

        <div class="form-group mt-3" id="enddate-container" style="display: none;">
            <label for="enddate">End Date</label>
            <input type="datetime-local" id="enddate" name="enddate" class="form-control">
            
            <!-- Buttons for 2 days, 1 week, and 1 month -->
            <div class="mt-2">
                <button type="button" class="btn btn-outline-secondary" id="btn-2-days">2 Days</button>
                <button type="button" class="btn btn-outline-secondary" id="btn-1-week">1 Week</button>
                <button type="button" class="btn btn-outline-secondary" id="btn-1-month">1 Month</button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-4">Save & Next</button>
    </form>
</div>

<script>
    document.getElementById('visit_type').addEventListener('change', function() {
        const visitType = this.value;
        const endDateContainer = document.getElementById('enddate-container');
        
        // Show or hide end date and buttons based on the selected visit type
        if (visitType === 'multiple') {
            endDateContainer.style.display = 'block';
        } else {
            endDateContainer.style.display = 'none';
        }
    });

    // Function to set the start and end date
    function setDateRange(daysToAdd) {
        const startDateInput = document.getElementById('startdate');
        const endDateInput = document.getElementById('enddate');

        // Get the current start date (or today's date if not set)
        const startDate = startDateInput.value ? new Date(startDateInput.value) : new Date();

        // Set the start date in the input field
        startDateInput.value = startDate.toISOString().slice(0, 16); // Format as yyyy-MM-ddTHH:mm

        // Calculate the end date based on the number of days to add
        const endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + daysToAdd);
        endDateInput.value = endDate.toISOString().slice(0, 16); // Format as yyyy-MM-ddTHH:mm
    }

    // Event listeners for the buttons
    document.getElementById('btn-2-days').addEventListener('click', function() {
        setDateRange(2);  // Add 2 days
    });

    document.getElementById('btn-1-week').addEventListener('click', function() {
        setDateRange(7);  // Add 7 days (1 week)
    });

    document.getElementById('btn-1-month').addEventListener('click', function() {
        setDateRange(30);  // Add 30 days (1 month)
    });
</script>
