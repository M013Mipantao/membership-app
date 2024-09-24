
$(document).ready(function() {
    // Initialize Select2 on the membership select element
    $('#guest-member').select2({
        placeholder: 'Search for a Membership ID',
        allowClear: true
    });

    // On membership selection change, fetch and update guest table
    $('#guest-member').on('change', function() {
        let memberId = $(this).val();
        if (memberId) {
            $.ajax({
                url: `/guests/by-member/${memberId}`,
                method: 'GET',
                success: function(data) {
                    let tableBody = $('#guest-table tbody');
                    tableBody.empty(); // Clear existing rows
                    console.log(data);
                    $.each(data, function(index, guest) {
                        let qrCodeImage = `<img src="${guest.qr_code}" alt="QR Code" class="qr-code-img" style="margin: 5px;">`;
                        // var qrCodeImage = 'data:image/png;base64,' + guest.qr_code;
                        $('#qrCode').attr('src', qrCodeImage);
                        tableBody.append(`
                            <tr>
                                <td>${guest.id}</td>
                                <td>${guest.guests_name}</td>
                                <td>${guest.guests_email}</td>
                                <td>${qrCodeImage}</td>
                                <td>
                                    <form action="/guests/${guest.id}" method="POST" style="display:inline;">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        `);
                    });
                },
                error: function(xhr) {
                    console.error(xhr);
                }
            });
        } else {
            $('#guest-table tbody').empty(); // Clear table if no membership selected
        }
    });
});