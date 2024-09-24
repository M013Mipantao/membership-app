@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        @foreach($guests as $guest)
            <tr data-id="{{ $guest->id }}">
                <td>
                    <span class="guest-name">{{ $guest->guests_name }}</span>
                    <input type="text" class="form-control guest-name-input" value="{{ $guest->guests_name }}" style="display:none;">
                </td>
                <td>
                    <span class="guest-email">{{ $guest->guests_email }}</span>
                    <input type="email" class="form-control guest-email-input" value="{{ $guest->guests_email }}" style="display:none;">
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


<script>
document.querySelectorAll('tr').forEach(row => {
    row.addEventListener('dblclick', function() {
        const id = this.dataset.id;
        const nameSpan = this.querySelector('.guest-name');
        const nameInput = this.querySelector('.guest-name-input');
        const emailSpan = this.querySelector('.guest-email');
        const emailInput = this.querySelector('.guest-email-input');

        // Toggle visibility
        nameSpan.style.display = 'none';
        nameInput.style.display = 'block';
        emailSpan.style.display = 'none';
        emailInput.style.display = 'block';

        nameInput.focus();

        // Update on blur or pressing enter
        nameInput.addEventListener('blur', () => updateGuest(id, nameInput.value, emailInput.value));
        emailInput.addEventListener('blur', () => updateGuest(id, nameInput.value, emailInput.value));
        emailInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                updateGuest(id, nameInput.value, emailInput.value);
            }
        });
    });
});

function updateGuest(id, name, email) {
    fetch(`/select-guests/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ name: name, email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload the page to see changes
        }
    });
}
</script>

