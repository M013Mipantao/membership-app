@extends('layouts.single_page_ui')
@include('partials.navbar_singlepage')

@section('content')

<style>
 .timeline {
  position: relative;
  font-size: 10px;
}

.timeline-item {
  display: flex;
  position: relative;
  padding-left: 40px;
}

.timeline-item-marker {
  position: absolute;
  left: 0;
  top: 0;
  font-size: 20px;
}

.timeline-item-content {
  flex: 1;
  padding-left: 15px;
}

.timeline-item-title {
  font-size: 1rem;
  font-weight: bold;
}

.timeline-item-content p {
  margin: 5px 0;
}
.list-actions button span, 
.list-actions a span {
    font-weight: normal;
}


/* Mobile */
.list-group {
  overflow: hidden;
}

.list-group-item {
  position: relative;
  display: flex;
  align-items: center;
  overflow: hidden;
  padding: 10px 15px;
  background: #f8f9fa;
  margin-bottom: 5px;
}

.list-content {
  flex: 1;
  z-index: 1;
  transition: transform 0.3s ease;
}

.list-actions {
  position: absolute;
  right: 0;
  top: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  background: #ffffff;
  transition: width 0.3s ease;
  width: 0;
  overflow: hidden;
}

.list-actions button {
  margin-left: 5px;
}

.list-group-item.swiped .list-content {
  transform: translateX(-120px); /* Adjust width based on buttons */
}

.list-group-item.swiped .list-actions {
  width: 140px; /* Adjust width based on buttons */
}

.list-actions button, 
.list-actions a {
    margin: 0;
    padding: 0;
    border-radius: 0; /* Optional: Removes rounded corners */
}
.list-actions {
    padding: 0;
    margin: 0;
}


@media (max-width: 768px) {

    /* Modify QR Codes Section
    .qr-codes-section .card {
        border: none;
        box-shadow: none;
        margin: 0;
        padding: 0;
    } 
    .qr-codes-section .card-header {
        display: none;
    }

    .qr-codes-section .card-body {
        padding: 0;
    } */

    .list-group-item {
        border: none;
        background: #ffffff;
        margin-bottom: 0;
        padding: 10px 10px;
    }

    .list-group {
        border: none;
    }
    
}
</style>
<div class="container-fluid">
    {{-- <h1 class="h3 mb-4 text-gray-800">Dashboard</h1> --}}
    <div class="d-flex align-items-center mb-3">
       <!-- Back Link -->
        <a onclick="window.history.back();" style="cursor: pointer; color: #007bff;">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <!-- Tabs Navigation -->
    <ul class="nav nav-pills mb-4" id="dashboardTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="transactions-tab" data-bs-toggle="pill" href="#transactions" role="tab" aria-controls="transactions" aria-selected="true">Transactions</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="qr-codes-tab" data-bs-toggle="pill" href="#qr-codes" role="tab" aria-controls="qr-codes" aria-selected="false">QR Codes</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="dashboardTabsContent">
        <!-- Transactions Tab -->
        <div class="tab-pane fade show active" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">
            <!-- Date Range Picker -->
            <div class="col-md-4 col-md-offset-4 form-group">
                <label for="daterange">Date Range</label>
                <input type="text" class="form-control" id="daterange" name="daterange" />
            </div>
            <div class="row">
                <div class="col-sm-12 mb-3 mb-sm-0 transactions-section">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Transactions</h6>
                        </div>
                        <div class="card-body">
                            @php
                                $transactions = $data['transactions']['msg'] ?? [];
                            @endphp
                
                @if(empty($transactions))
                <p class="text-center text-muted">No transactions available for the selected range.</p>
            @else
                <div class="notification-panel">
                    @foreach(json_decode($transactions, true) as $transaction)
                        <div class="notification-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="notification-icon">
                                    @if($transaction['type'] === 'Payment')
                                    <i class="fas fa-check-circle text-success"></i>
                                    @elseif($transaction['type'] === 'Refund')
                                        <i class="fas fa-times-circle text-danger"></i>
                                    @elseif($transaction['type'] === 'Entitlement')
                                        <i class="fas fa-gift text-warning"></i>
                                    @elseif($transaction['type'] === 'Utilized')
                                        <i class="fas fa-cart-arrow-down text-info"></i>
                                    @elseif($transaction['type'] === 'OB' || $transaction['type'] === 'CB')
                                        <i class="fas fa-balance-scale text-primary"></i>
                                    @else
                                        <i class="fas fa-info-circle text-secondary"></i>
                                    @endif
                                
                                </div>
                                <div class="notification-content ml-3">
                                    <p class="mb-1">{{ $transaction['particular'] }} - {{ number_format($transaction['value'], 2) }}</p>
                                    <span class="text-xs text-gray-500">{{$transaction['date']}} -  {{\Carbon\Carbon::parse($transaction['date'])->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
                        </div>
                    </div>
                </div>
                
            </div>
        </div>


<!-- QR Codes Tab -->
<div class="tab-pane fade" id="qr-codes" role="tabpanel" aria-labelledby="qr-codes-tab">
    <div class="row">
        <!-- Mobile View QR Codes Section (Visible only on mobile) -->
        <div class="col-12 qr-codes-section d-block d-sm-none">
            <div class="card b-1 mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">QR Codes</h6>
                </div>
                <div class="card-body">
                    @php
                        $qrCodes = $data['qr_codes'] ?? [];
                    @endphp

                    @if(empty($qrCodes))
                        <p class="text-center text-muted">No active QR codes available.</p>
                    @else
                    <ul class="list-group list-group-flush border">
                        @foreach($qrCodes as $qrCode)
                            <li class="list-group-item d-flex justify-content-between align-items-start text-muted border-bottom">
                                <div class="list-content">
                                    <strong>Guest:</strong> {{ $qrCode->guest->guests_name ?? 'N/A' }} <br>
                                    <strong>Visit:</strong> {{ $qrCode->startdate }} - {{ $qrCode->enddate }} <br>
                                    <strong>Status:</strong> {{ $qrCode->status }} <br>
                                </div>
                                <div class="list-actions d-flex flex-grow-1">
                                    <button class="btn btn-danger flex-fill w-100 h-100 d-flex flex-column align-items-center justify-content-center" data-id="{{ $qrCode->id }}" >
                                        <i class="fa fa-trash mb-1"></i>
                                        <span>Delete</span>
                                    </button>
                                    <a href="{{ $qrCode->qr_code }}" class="btn btn-primary flex-fill w-100 h-100 d-flex flex-column align-items-center justify-content-center" target="_blank">
                                        <i class="fa fa-download mb-1"></i>
                                        <span>Download</span>
                                    </a>
                                </div>
                                   <!-- Right Icon (Floating in the middle right corner) -->
                                   <i class="fa fa-angle-left text-xs text-gray-500 position-absolute top-50 end-0 translate-middle-y"></i>
                            </li>
                        
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- Web View QR Codes Section (Visible only on desktop and larger screens) -->
        <div class="col-12 qr-codes-section-web d-none d-sm-block">
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">QR Codes</h6>
                </div>
                <div class="card-body">
                    @php
                        $qrCodes = $data['qr_codes'] ?? [];
                    @endphp

                    @if(empty($qrCodes))
                        <p class="text-center text-muted">No active QR codes available.</p>
                    @else
                        <table id="qrCodesTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Guest</th>
                                    <th>Visit Dates</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($qrCodes as $qrCode)
                                    <tr>
                                        <td>{{ $qrCode->guest->guests_name ?? 'N/A' }}</td>
                                        <td>{{ $qrCode->startdate }} - {{ $qrCode->enddate }}</td>
                                        <td>{{ $qrCode->status }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm" data-id="{{ $qrCode->id }}" >
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                            <a href="{{ $qrCode->qr_code }}" class="btn btn-primary btn-sm" target="_blank">
                                                <i class="fa fa-download"></i> Download
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


        
    </div>
</div>

@endsection

@push('scripts')
    <script>
$(document).ready(function () {
        // Initialize Date Range Picker
        $('#daterange').daterangepicker({
            locale: { format: 'YYYY-MM-DD' }
        });

        $('#btn-qr-delete').on('click', () => {
            alert("TEST");
        });


        // Event listener for applying a new date range
        $('#daterange').on('apply.daterangepicker', function (e, picker) {
            const from = picker.startDate.format('YYYY-MM-DD');
            const to = picker.endDate.format('YYYY-MM-DD');

        // AJAX request to fetch filtered transactions
        $.ajax({
            url: "{{ route('transactionapi', ['member_id' => session('member')->id]) }}",
            type: 'GET',
            data: { from, to },
            success: function (response) {
                if (response.error) {
                    // Display error message
                    $('.transactions-section .card-body').html(`<p class="text-center text-danger">${response.error}</p>`);
                } else if (response.transactions && response.transactions.msg) {
                    // Parse transactions JSON string
                    const transactions = JSON.parse(response.transactions.msg);

                    if (transactions.length > 0) {
                        // Generate HTML for transactions
                        let transactionsHtml = '';
                        transactions.forEach(transaction => {
                            transactionsHtml += `
                                <div class="notification-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="notification-icon">
                                            ${getTransactionIcon(transaction.type)}
                                        </div>
                                        <div class="notification-content ml-3">
                                            <p class="mb-1">${transaction.particular} - ${parseFloat(transaction.value).toFixed(2)}</p>
                                            <span class="text-xs text-gray-500">${transaction.date} - ${moment(transaction.date).fromNow()}</span>
                                        </div>
                                    </div>
                                </div>`;
                        });
                        // Update the UI with transactions
                        $('.transactions-section .card-body').html(transactionsHtml);
                    } else {
                        // No transactions for the selected range
                        $('.transactions-section .card-body').html('<p class="text-center text-muted">No transactions available for the selected range.</p>');
                    }
                } else {
                    // Handle case with no transactions
                    $('.transactions-section .card-body').html('<p class="text-center text-muted">No transactions available for the selected range.</p>');
                }
            },
            error: function (xhr) {
                // Handle AJAX errors
                console.error('Error fetching transactions:', xhr.responseText);
                $('.transactions-section .card-body').html('<p class="text-center text-danger">An error occurred while fetching transactions.</p>');
            }
        });
    });

    // Helper function to get the appropriate icon for a transaction type
    function getTransactionIcon(type) {
        if (type === 'Payment') {
            return '<i class="fas fa-check-circle text-success"></i>';
        } else if (type === 'Refund') {
            return '<i class="fas fa-times-circle text-danger"></i>';
        } else if (type === 'Entitlement') {
            return '<i class="fas fa-gift text-warning"></i>';
        } else if (type === 'Utilized') {
            return '<i class="fas fa-cart-arrow-down text-info"></i>';
        } else if (type === 'OB' || type === 'CB') {
            return '<i class="fas fa-balance-scale text-primary"></i>';
        }
        return '<i class="fas fa-info-circle text-secondary"></i>';
    }


    function initializeSwipeEffects() {
        document.querySelectorAll('.list-group-item').forEach(item => {
            const hammer = new Hammer(item);

            // Swipe left action
            hammer.on('swipeleft', () => {
                item.classList.add('swiped');
            });

            // Swipe right action
            hammer.on('swiperight', () => {
                item.classList.remove('swiped');
            });

            // Delete button event listener
            item.querySelector('.btn-danger').addEventListener('click', function () {
                const qrCodeId = this.getAttribute('data-id'); // Get the QR code ID
                if (confirm('Are you sure you want to delete this QR code?')) {
                    $.ajax({
                        url: `/qr-codes/${qrCodeId}/status`,
                        type: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            status: 'InActive' // New status value
                        },
                        success: function (response) {
                            console.log(response.message);

                            // Reload the QR codes list
                            reloadQrCodesList();
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    }

    // Updated reloadQrCodesList function
    function reloadQrCodesList() {
        $.ajax({
            url: '/qr-codes', // Replace with the correct route to fetch the updated QR codes list
            type: 'GET',
            success: function (response) {
                const qrCodesSection = document.querySelector('.qr-codes-section');
                if (response.qr_codes && response.qr_codes.length > 0) {
                    let listItems = '';

                    response.qr_codes.forEach(qrCode => {
                        listItems += `
                            <li class="list-group-item d-flex justify-content-between align-items-start text-muted border-bottom">
                                <div class="list-content">
                                    <strong>Guest:</strong> ${qrCode.guest?.guests_name ?? 'N/A'} <br>
                                    <strong>Visit:</strong> ${qrCode.startdate} - ${qrCode.enddate} <br>
                                    <strong>Status:</strong> ${qrCode.status} <br>
                                </div>
                                <div class="list-actions d-flex flex-grow-1">
                                    <button class="btn btn-danger flex-fill w-100 h-100 d-flex flex-column align-items-center justify-content-center" data-id="${qrCode.id}">
                                        <i class="fa fa-trash mb-1"></i>
                                        <span>Delete</span>
                                    </button>
                                    <a href="${qrCode.qr_code}" class="btn btn-primary flex-fill w-100 h-100 d-flex flex-column align-items-center justify-content-center" target="_blank">
                                        <i class="fa fa-download mb-1"></i>
                                        <span>Download</span>
                                    </a>
                                </div>
                                <i class="fa fa-angle-left text-xs text-gray-500 position-absolute top-50 end-0 translate-middle-y"></i>
                            </li>
                        `;
                    });

                    qrCodesSection.innerHTML = `
                        <ul class="list-group list-group-flush border">
                            ${listItems}
                        </ul>
                    `;
                } else {
                    qrCodesSection.innerHTML = `<p class="text-center text-muted">No active QR codes available.</p>`;
                }

                // Reinitialize swipe effects and event listeners
                initializeSwipeEffects();
            },
            error: function (xhr) {
                console.error('Failed to fetch QR codes:', xhr.responseText);
            }
        });
    }

    // Initial call to set up swipe effects on page load
    initializeSwipeEffects();
    document.addEventListener('DOMContentLoaded', () => {
        // Get the current path from the URL
        const path = window.location.pathname;

        // Determine the active tab based on the URL
        let activeTabId;
        if (path.includes('/transactions')) {
        activeTabId = 'transaction-tab';
        } else if (path.includes('/qr_codes')) {
        activeTabId = 'qr-code-tab';
        }

        if (activeTabId) {
        // Activate the corresponding tab
        const activeTab = document.getElementById(activeTabId);
        const tab = new bootstrap.Tab(activeTab);
        tab.show();
        }
    });

});


    
    </script>
@endpush
