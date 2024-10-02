<!-- resources/views/transactions/index.blade.php -->

@extends('layouts.index')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Transactions</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Transaction Records</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="transactionTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date Time</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Membership</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->datetime }}</td>
                            <td>{{ $transaction->name }}</td>
                            <td>{{ $transaction->type }}</td>
                            <td>{{ $transaction->membership }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SB Admin Pro's DataTables Scripts -->
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('#transactionTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("transactions.fetch") }}',
        columns: [
            { data: 'datetime', name: 'datetime' },
            { data: 'name', name: 'name' },
            { data: 'type', name: 'type' },
            { data: 'membership', name: 'membership' }
        ],
    });
});
</script>
@endpush
