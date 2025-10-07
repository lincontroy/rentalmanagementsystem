<!-- resources/views/payments/index.blade.php -->
@extends('layouts.app')

@section('title', 'Payments')
@section('page-title', 'Payments Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Payments</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Payments</h3>
        <div class="card-tools">
            <a href="{{ route('payments.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Record New Payment
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fas fa-check"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>KSh {{ number_format($payments->where('status', 'completed')->sum('amount'), 2) }}</h3>
                        <p>Total Completed</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>KSh {{ number_format($payments->where('status', 'pending')->sum('amount'), 2) }}</h3>
                        <p>Pending Payments</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $payments->count() }}</h3>
                        <p>Total Transactions</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $payments->where('type', 'rent')->count() }}</h3>
                        <p>Rent Payments</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-home"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="propertyFilter">Filter by Property</label>
                    <select class="form-control" id="propertyFilter">
                        <option value="">All Properties</option>
                        @foreach($properties as $property)
                        <option value="{{ $property->id }}">{{ $property->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="typeFilter">Filter by Type</label>
                    <select class="form-control" id="typeFilter">
                        <option value="">All Types</option>
                        <option value="rent">Rent</option>
                        <option value="deposit">Deposit</option>
                        <option value="utility">Utility</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="statusFilter">Filter by Status</label>
                    <select class="form-control" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="monthFilter">Filter by Month</label>
                    <input type="month" class="form-control" id="monthFilter" value="{{ date('Y-m') }}">
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Transaction ID</th>
                        <th>Tenant</th>
                        <th>Property</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr class="payment-row" 
                        data-property="{{ $payment->property_id }}"
                        data-type="{{ $payment->type }}"
                        data-status="{{ $payment->status }}"
                        data-month="{{ $payment->month_year }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <small class="text-muted">{{ $payment->transaction_id }}</small>
                        </td>
                        <td>
                            <strong>{{ $payment->tenant->name ?? 'N/A' }}</strong>
                            @if($payment->tenant)
                            <br><small class="text-muted">{{ $payment->tenant->phone ?? '' }}</small>
                            @endif
                        </td>
                        <td>{{ $payment->property->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $payment->type == 'rent' ? 'primary' : ($payment->type == 'deposit' ? 'success' : 'info') }}">
                                {{ ucfirst($payment->type) }}
                            </span>
                        </td>
                        <td class="font-weight-bold">KSh {{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td>
                            <span class="badge badge-secondary">
                                {{ ucfirst($payment->payment_method) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $payment->status == 'completed' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('payments.show', $payment) }}" class="btn btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('payments.destroy', $payment) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this payment?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No payments found</p>
                            <a href="{{ route('payments.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Record Your First Payment
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <th colspan="5" class="text-right">Total:</th>
                        <th class="text-success">KSh {{ number_format($payments->sum('amount'), 2) }}</th>
                        <th colspan="4"></th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-right">Completed:</th>
                        <th class="text-success">KSh {{ number_format($payments->where('status', 'completed')->sum('amount'), 2) }}</th>
                        <th colspan="4"></th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-right">Pending:</th>
                        <th class="text-warning">KSh {{ number_format($payments->where('status', 'pending')->sum('amount'), 2) }}</th>
                        <th colspan="4"></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
        <div class="row mt-3">
            <div class="col-md-6">
                <p class="text-muted">
                    Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} entries
                </p>
            </div>
            <div class="col-md-6">
                <div class="float-right">
                    {{ $payments->links() }}
                </div>
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

        function filterPayments() {
            const propertyValue = propertyFilter.value;
            const typeValue = typeFilter.value;
            const statusValue = statusFilter.value;
            const monthValue = monthFilter.value;

            paymentRows.forEach(row => {
                const propertyMatch = !propertyValue || row.dataset.property === propertyValue;
                const typeMatch = !typeValue || row.dataset.type === typeValue;
                const statusMatch = !statusValue || row.dataset.status === statusValue;
                const monthMatch = !monthValue || row.dataset.month === monthValue;

                if (propertyMatch && typeMatch && statusMatch && monthMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
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
    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    
    .payment-row:hover {
        background-color: #f8f9fa;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .badge {
        font-size: 0.75em;
    }
</style>
@endsection