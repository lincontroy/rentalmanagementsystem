<!-- resources/views/payments/index.blade.php -->
@extends('layouts.app')

@section('title', 'Payments')
@section('page-title', 'Payments Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Payments</li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h3 class="card-title mb-0">Payment Records</h3>
        <div class="card-tools">
            <a href="{{ route('payments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle mr-1"></i> Record New Payment
            </a>
        </div>
    </div>
    
    <div class="card-body p-4">
        <!-- Success Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="row mb-5">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Completed
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    KSh {{ number_format($payments->where('status', 'completed')->sum('amount'), 2) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending Payments
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    KSh {{ number_format($payments->where('status', 'pending')->sum('amount'), 2) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Transactions
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $payments->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-receipt fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Rent Payments
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $payments->where('type', 'rent')->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-home fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light py-3">
                <h5 class="mb-0">
                    <i class="fas fa-filter mr-2 text-primary"></i>Filter Payments
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label for="propertyFilter" class="form-label fw-semibold">Property</label>
                        <select class="form-control" id="propertyFilter">
                            <option value="">All Properties</option>
                            @foreach($properties as $property)
                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label for="typeFilter" class="form-label fw-semibold">Payment Type</label>
                        <select class="form-control" id="typeFilter">
                            <option value="">All Types</option>
                            <option value="rent">Rent</option>
                            <option value="deposit">Deposit</option>
                            <option value="utility">Utility</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label for="statusFilter" class="form-label fw-semibold">Status</label>
                        <select class="form-control" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label for="monthFilter" class="form-label fw-semibold">Month</label>
                        <input type="month" class="form-control" id="monthFilter" value="{{ date('Y-m') }}">
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                        <i class="fas fa-redo mr-1"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Desktop Table (hidden on mobile) -->
        <div class="card border-0 shadow-sm d-none d-md-block">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Payment History</h5>
                <span class="badge badge-primary bg-primary">{{ $payments->count() }} records</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0 ps-4">#</th>
                                <th class="border-0">Transaction ID</th>
                                <th class="border-0">Tenant</th>
                                <th class="border-0">Property</th>
                                <th class="border-0">Type</th>
                                <th class="border-0">Amount</th>
                                <th class="border-0">Payment Date</th>
                                <th class="border-0">Method</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 pe-4 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                            <tr class="payment-row align-middle" 
                                data-property="{{ $payment->property_id }}"
                                data-type="{{ $payment->type }}"
                                data-status="{{ $payment->status }}"
                                data-month="{{ $payment->month_year }}">
                                <td class="ps-4">{{ $loop->iteration }}</td>
                                <td>
                                    <code class="text-muted small">{{ $payment->transaction_id }}</code>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold">{{ $payment->tenant->name ?? 'N/A' }}</span>
                                        @if($payment->tenant && $payment->tenant->phone)
                                        <small class="text-muted">{{ $payment->tenant->phone }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $payment->property->name ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $typeBadge = [
                                            'rent' => 'primary',
                                            'deposit' => 'success', 
                                            'utility' => 'info',
                                            'other' => 'secondary'
                                        ][$payment->type] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $typeBadge }}">
                                        {{ ucfirst($payment->type) }}
                                    </span>
                                </td>
                                <td class="fw-bold text-nowrap">KSh {{ number_format($payment->amount, 2) }}</td>
                                <td class="text-nowrap">{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ ucfirst($payment->payment_method) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusBadge = [
                                            'completed' => 'success',
                                            'pending' => 'warning',
                                            'failed' => 'danger'
                                        ][$payment->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusBadge }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-outline-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this payment?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted">No payments found</h5>
                                        <p class="text-muted mb-4">Get started by recording your first payment</p>
                                        <a href="{{ route('payments.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus mr-1"></i> Record Your First Payment
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mobile Cards (visible only on mobile) -->
        <div class="d-block d-md-none">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Payment History</h5>
                <span class="badge badge-primary bg-primary">{{ $payments->count() }} records</span>
            </div>
            
            @forelse($payments as $payment)
            <div class="card payment-card mb-3 shadow-sm payment-row"
                 data-property="{{ $payment->property_id }}"
                 data-type="{{ $payment->type }}"
                 data-status="{{ $payment->status }}"
                 data-month="{{ $payment->month_year }}">
                <div class="card-body">
                    <!-- Header Section -->
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="card-title mb-1 text-primary">KSh {{ number_format($payment->amount, 2) }}</h6>
                            <small class="text-muted">#{{ $payment->transaction_id }}</small>
                        </div>
                        <div class="text-end">
                            @php
                                $statusBadge = [
                                    'completed' => 'success',
                                    'pending' => 'warning',
                                    'failed' => 'danger'
                                ][$payment->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $statusBadge }} mb-1">
                                {{ ucfirst($payment->status) }}
                            </span>
                            <br>
                            @php
                                $typeBadge = [
                                    'rent' => 'primary',
                                    'deposit' => 'success', 
                                    'utility' => 'info',
                                    'other' => 'secondary'
                                ][$payment->type] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $typeBadge }}">
                                {{ ucfirst($payment->type) }}
                            </span>
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div class="row mt-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Tenant</small>
                            <strong class="d-block">{{ $payment->tenant->name ?? 'N/A' }}</strong>
                            @if($payment->tenant && $payment->tenant->phone)
                            <small class="text-muted">{{ $payment->tenant->phone }}</small>
                            @endif
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Property</small>
                            <strong class="d-block">{{ $payment->property->name ?? 'N/A' }}</strong>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-6">
                            <small class="text-muted d-block">Payment Date</small>
                            <strong class="d-block">{{ $payment->payment_date->format('M d, Y') }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Method</small>
                            <span class="badge bg-light text-dark border">
                                {{ ucfirst($payment->payment_method) }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                        <small class="text-muted">#{{ $loop->iteration }}</small>
                        <div class="btn-group">
                            <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this payment?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No payments found</h5>
                    <p class="text-muted mb-4">Get started by recording your first payment</p>
                    <a href="{{ route('payments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Record Your First Payment
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <p class="text-muted mb-0">
                Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} entries
            </p>
            <div>
                {{ $payments->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const propertyFilter = document.getElementById('propertyFilter');
        const typeFilter = document.getElementById('typeFilter');
        const statusFilter = document.getElementById('statusFilter');
        const monthFilter = document.getElementById('monthFilter');
        const paymentRows = document.querySelectorAll('.payment-row');
        const paymentCards = document.querySelectorAll('.payment-card');

        function filterPayments() {
            const propertyValue = propertyFilter.value;
            const typeValue = typeFilter.value;
            const statusValue = statusFilter.value;
            const monthValue = monthFilter.value;

            let visibleCount = 0;
            
            // Filter table rows (desktop)
            paymentRows.forEach(row => {
                const propertyMatch = !propertyValue || row.dataset.property === propertyValue;
                const typeMatch = !typeValue || row.dataset.type === typeValue;
                const statusMatch = !statusValue || row.dataset.status === statusValue;
                const monthMatch = !monthValue || row.dataset.month === monthValue;

                if (propertyMatch && typeMatch && statusMatch && monthMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Filter cards (mobile)
            paymentCards.forEach(card => {
                const propertyMatch = !propertyValue || card.dataset.property === propertyValue;
                const typeMatch = !typeValue || card.dataset.type === typeValue;
                const statusMatch = !statusValue || card.dataset.status === statusValue;
                const monthMatch = !monthValue || card.dataset.month === monthValue;

                if (propertyMatch && typeMatch && statusMatch && monthMatch) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Update record count badge
            const badges = document.querySelectorAll('.card-header .badge, .d-md-none .badge');
            badges.forEach(badge => {
                badge.textContent = `${visibleCount} records`;
            });
        }

        propertyFilter.addEventListener('change', filterPayments);
        typeFilter.addEventListener('change', filterPayments);
        statusFilter.addEventListener('change', filterPayments);
        monthFilter.addEventListener('change', filterPayments);

        // Reset filters
        window.resetFilters = function() {
            propertyFilter.value = '';
            typeFilter.value = '';
            statusFilter.value = '';
            monthFilter.value = '{{ date('Y-m') }}';
            filterPayments();
        }
    });
</script>

<style>
    .card {
        border: none;
        border-radius: 0.5rem;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.875rem;
        letter-spacing: 0.5px;
        color: #6c757d;
    }
    
    .payment-row:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .border-left-success {
        border-left: 0.25rem solid #28a745 !important;
    }
    
    .border-left-warning {
        border-left: 0.25rem solid #ffc107 !important;
    }
    
    .border-left-info {
        border-left: 0.25rem solid #17a2b8 !important;
    }
    
    .border-left-primary {
        border-left: 0.25rem solid #007bff !important;
    }
    
    .btn-group-sm > .btn {
        border-radius: 0.375rem;
        margin-left: 0.25rem;
    }
    
    .badge {
        font-size: 0.75em;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    /* Mobile card specific styles */
    .payment-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .payment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
    }
    
    .payment-card .card-title {
        font-size: 1.1rem;
    }
</style>
@endsection