<!-- resources/views/reports/rent.blade.php -->
@extends('layouts.app')

@section('title', 'Rent Report')
@section('page-title', 'Rent Collection Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Reports</a></li>
    <li class="breadcrumb-item active">Rent Report</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Rent Collection Report</h3>
        <div class="card-tools">
            <button class="btn btn-success btn-sm" onclick="exportToExcel()">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button class="btn btn-danger btn-sm" onclick="exportToPDF()">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Summary Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>KSh {{ number_format($totalRent, 2) }}</h3>
                        <p>Total Rent Collected</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $totalTransactions }}</h3>
                        <p>Total Transactions</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>KSh {{ number_format($pendingRent, 2) }}</h3>
                        <p>Pending Rent</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ number_format($collectionRate, 1) }}%</h3>
                        <p>Collection Rate</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Monthly Rent Collection Trend</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="rentChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Payment Methods</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="paymentMethodChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detailed Rent Payments</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Tenant</th>
                                        <th>Property</th>
                                        <th>Unit</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Transaction ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rentPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                        <td>{{ $payment->tenant->name }}</td>
                                        <td>{{ $payment->property->name }}</td>
                                        <td>{{ $payment->tenant->unit->unit_number }}</td>
                                        <td>KSh {{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ ucfirst($payment->payment_method) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $payment->status == 'completed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $payment->transaction_id }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function exportToExcel() {
        // Implement Excel export
        alert('Excel export functionality would be implemented here');
    }

    function exportToPDF() {
        // Implement PDF export
        alert('PDF export functionality would be implemented here');
    }

    // Charts
    $(document).ready(function() {
        // Rent Collection Trend Chart
        var rentCtx = document.getElementById('rentChart').getContext('2d');
        var rentChart = new Chart(rentCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyTrends->pluck('month')) !!},
                datasets: [{
                    label: 'Rent Collected',
                    data: {!! json_encode($monthlyTrends->pluck('amount')) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Payment Method Chart
        var methodCtx = document.getElementById('paymentMethodChart').getContext('2d');
        var methodChart = new Chart(methodCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($paymentMethods->pluck('method')) !!},
                datasets: [{
                    data: {!! json_encode($paymentMethods->pluck('count')) !!},
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0'
                    ]
                }]
            }
        });
    });
</script>
@endsection