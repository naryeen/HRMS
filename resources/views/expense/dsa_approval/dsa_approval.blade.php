@extends('layout') <!-- Extend your layout file -->

@section('content')
<div class="container">
    <h1>Dsa Approval Applications</h1>

    <form action="{{ route('dsa.approval.index') }}" method="GET">
        <div class="form-group">
            <label for="statusFilter">Filter by Status:</label>
            <select class="form-control" name="status" id="statusFilter">
                <option value="">All</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
            <button type="submit" class="btn btn-primary mt-2">Apply Filter</button>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" id="selectAll"> <!-- Add Select All checkbox -->
                </th>
                <th>Employee</th>
                <th>Total Amount Adjusted</th>
                <th>Net Payable Amount</th>
                <th>Balance Amount</th>
                <th>Status</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($dsaSettlements as $dsaSettlement)
            <tr>
                <td>
                    <input type="checkbox" class="selectSingle" name="selected[]" value="{{ $dsaSettlement->id }}">
                </td>
                <td>{{ $dsaSettlement->user->name }}</td>
                <td>{{ $dsaSettlement->total_amount_adjusted }}</td>
                <td>{{ $dsaSettlement->net_payable_amount }}</td>
                <td>{{ $dsaSettlement->balance_amount }}</td>
                <td>
                    <a href="{{ route('dsa-settlement.view', ['id' => $dsaSettlement->id]) }}"
                       style="color: {{ $dsaSettlement->status === 'pending' ? 'orange' : ($dsaSettlement->status === 'approved' ? 'green' : 'red') }};">
                        {{ $dsaSettlement->status }}
                    </a>
                </td>
                            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        <button class="btn btn-success" id="approveButton">Approve</button>
        <button class="btn btn-danger" id="rejectButton">Reject</button>
    </div>
</div>

<script>
    // Add JavaScript to handle "Select All" functionality
    document.getElementById('selectAll').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.selectSingle');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
@endsection