<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalProperties }}</h3>
                    <p>Properties</p>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
                <a href="{{ route('properties.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalTenants }}</h3>
                    <p>Active Tenants</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('tenants.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalUnits }}</h3>
                    <p>Total Units</p>
                </div>
                <div class="icon">
                    <i class="fas fa-home"></i>
                </div>
                <a href="{{ route('units.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>KSh {{ number_format($monthlyRevenue, 2) }}</h3>
                    <p>Monthly Revenue</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <a href="{{ route('payments.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->

    <!-- Additional Stats Row -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $occupiedUnits }}</h3>
                    <p>Occupied Units</p>
                </div>
                <div class="icon">
                    <i class="fas fa-door-closed"></i>
                </div>
                <a href="{{ route('units.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-light">
                <div class="inner">
                    <h3>{{ $vacantUnits ?? 0 }}</h3>
                    <p>Vacant Units</p>
                </div>
                <div class="icon">
                    <i class="fas fa-door-open"></i>
                </div>
                <a href="{{ route('units.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-dark">
                <div class="inner">
                    <h3>KSh {{ number_format($totalArrears ?? 0, 2) }}</h3>
                    <p>Total Arrears</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="{{ route('reports.arrears') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ number_format($collectionRate, 1) }}%</h3>
                    <p>Collection Rate</p>
                </div>
                <div class="icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <a href="{{ route('reports.rent') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Main row -->
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Property Performance
                    </h3>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
                            @if($properties->count() > 0)
                                <canvas id="propertyChart" height="300" style="height: 300px;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No property data available for chart</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- Recent Payments -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Payments</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    @if($recentPayments->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tenant</th>
                                    <th>Property</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $payment)
                                <tr>
                                    <td>
                                        @if($payment->tenant)
                                            {{ $payment->tenant->name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->property)
                                            {{ $payment->property->name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>KSh {{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $payment->status == 'completed' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-receipt fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No recent payments found</p>
                        </div>
                    @endif
                </div>
                <!-- /.card-body -->
                @if($recentPayments->count() > 0)
                <div class="card-footer text-center">
                    <a href="{{ route('payments.index') }}" class="uppercase">View All Payments</a>
                </div>
                @endif
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </section>
        <!-- /.Left col -->

        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">
            <!-- Properties List -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Properties Overview</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if($properties->count() > 0)
                        @foreach($properties as $property)
                        <div class="progress-group">
                            {{ $property->name }}
                            <span class="float-right">
                                <b>{{ $property->active_tenants_count ?? 0 }}</b>/{{ $property->units_count ?? 0 }}
                            </span>
                            <div class="progress progress-sm">
                                @php
                                    $occupancyRate = $property->units_count > 0 ? 
                                        (($property->active_tenants_count ?? 0) / $property->units_count) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-primary" style="width: {{ $occupancyRate }}%"></div>
                            </div>
                            <small class="text-muted">
                                Occupancy: {{ number_format($occupancyRate, 1) }}% | 
                                Paybill: {{ $property->paybill_number }}
                            </small>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-building fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No properties found</p>
                            <a href="{{ route('properties.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add First Property
                            </a>
                        </div>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <a href="{{ route('tenants.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-user-plus"></i><br>
                                Add Tenant
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('payments.create') }}" class="btn btn-success btn-block">
                                <i class="fas fa-money-bill-wave"></i><br>
                                Record Payment
                            </a>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <a href="{{ route('properties.create') }}" class="btn btn-info btn-block">
                                <i class="fas fa-building"></i><br>
                                Add Property
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('reports.rent') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-chart-bar"></i><br>
                                View Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">System Status</h3>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Database
                            <span class="badge badge-success badge-pill">Connected</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Properties
                            <span class="badge badge-{{ $totalProperties > 0 ? 'success' : 'warning' }} badge-pill">
                                {{ $totalProperties }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Active Tenants
                            <span class="badge badge-{{ $totalTenants > 0 ? 'success' : 'warning' }} badge-pill">
                                {{ $totalTenants }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Monthly Revenue
                            <span class="badge badge-{{ $monthlyRevenue > 0 ? 'success' : 'warning' }} badge-pill">
                                KSh {{ number_format($monthlyRevenue, 0) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- right col -->
    </div>
    <!-- /.row (main row) -->
</div><!-- /.container-fluid -->
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Property Performance Chart - only initialize if we have data
        @if($properties->count() > 0)
        var ctx = document.getElementById('propertyChart').getContext('2d');
        var propertyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($properties->pluck('name')) !!},
                datasets: [{
                    label: 'Occupancy Rate %',
                    data: {!! json_encode($properties->map(function($property) {
                        return $property->units_count > 0 ? 
                            (($property->active_tenants_count ?? 0) / $property->units_count) * 100 : 0;
                    })) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toFixed(1) + '%';
                            }
                        }
                    }
                }
            }
        });
        @endif
    });
</script>
@endsection