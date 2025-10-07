<!-- resources/views/reports/arrears.blade.php -->
@extends('layouts.app')

@section('title', 'Arrears Report')
@section('page-title', 'Rent Arrears Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Reports</a></li>
    <li class="breadcrumb-item active">Arrears Report</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Rent Arrears Summary</h3>
    </div>
    <div class="card-body">
        <!-- Summary Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>KSh {{ number_format($totalArrears, 2) }}</h3>
                        <p>Total Arrears</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $tenantsWithArrears->count() }}</h3>
                        <p>Tenants in Arrears</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>KSh {{ number_format($averageArrears, 2) }}</h3>
                        <p>Average Arrears per Tenant</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format(($tenantsWithArrears->count() / max($tenantsWithArrears->count(), 1)) * 100, 1) }}%</h3>
                        <p>Collection Efficiency</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Arrears by Property -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Arrears by Property</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="arrearsByPropertyChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Top Defaulters</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tenant</th>
                                    <th>Property</th>
                                    <th>Arrears</th>
                                    <th>Days Overdue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tenantsWithArrears->sortByDesc('rent_balance')->take(5) as $tenant)
                                <tr>
                                    <td>{{ $tenant->name }}</td>
                                    <td>{{ $tenant->property->name }}</td>
                                    <td class="text-danger">KSh {{ number_format($tenant->rent_balance, 2) }}</td>
                                    <td>
                                        @php
                                            $daysOverdue = now()->diffInDays($tenant->lease_start_date->addMonth());
                                        @endphp
                                        <span class="badge badge-{{ $daysOverdue > 30 ? 'danger' : 'warning' }}">
                                            {{ $daysOverdue }} days
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Arrears Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Tenants with Arrears</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>Tenant</th>
                                        <th>Property</th>
                                        <th>Unit</th>
                                        <th>Phone</th>
                                        <th>Rent Balance</th>
                                        <th>Last Payment</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tenantsWithArrears as $tenant)
                                    <tr>
                                        <td>{{ $tenant->name }}</td>
                                        <td>{{ $tenant->property->name }}</td>
                                        <td>{{ $tenant->unit->unit_number }}</td>
                                        <td>{{ $tenant->phone }}</td>
                                        <td class="text-danger"><strong>KSh {{ number_format($tenant->rent_balance, 2) }}</strong></td>
                                        <td>
                                            @php
                                                $lastPayment = $tenant->payments->where('type', 'rent')->sortByDesc('payment_date')->first();
                                            @endphp
                                            {{ $lastPayment ? $lastPayment->payment_date->format('M d, Y') : 'No payments' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $tenant->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($tenant->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('payments.create') }}?tenant_id={{ $tenant->id }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-money-bill-wave"></i> Record Payment
                                            </a>
                                        </td>
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
    $(document).ready(function() {
        // Arrears by Property Chart
        var ctx = document.getElementById('arrearsByPropertyChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($arrearsByProperty->pluck('property_name')) !!},
                datasets: [{
                    label: 'Arrears Amount (KSh)',
                    data: {!! json_encode($arrearsByProperty->pluck('total_arrears')) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.8)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection