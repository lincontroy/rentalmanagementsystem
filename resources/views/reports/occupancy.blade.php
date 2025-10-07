<!-- resources/views/reports/occupancy.blade.php -->
@extends('layouts.app')

@section('title', 'Occupancy Report')
@section('page-title', 'Occupancy Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">Reports</a></li>
    <li class="breadcrumb-item active">Occupancy Report</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Property Occupancy Report</h3>
        <div class="card-tools">
            <button class="btn btn-success btn-sm" onclick="exportToExcel()">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button class="btn btn-danger btn-sm" onclick="window.print()">
                <i class="fas fa-print"></i> Print Report
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalUnits ?? 0 }}</h3>
                        <p>Total Units</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-home"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $occupiedUnits ?? 0 }}</h3>
                        <p>Occupied Units</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-door-closed"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $vacantUnits ?? 0 }}</h3>
                        <p>Vacant Units</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-door-open"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $maintenanceUnits ?? 0 }}</h3>
                        <p>Under Maintenance</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overall Occupancy Rate -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="info-box bg-gradient-primary">
                    <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Overall Occupancy Rate</span>
                        <span class="info-box-number">{{ number_format($occupancyRate ?? 0, 1) }}%</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $occupancyRate ?? 0 }}%"></div>
                        </div>
                        <span class="progress-description">
                            {{ $occupiedUnits ?? 0 }} of {{ $totalUnits ?? 0 }} units occupied
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property-wise Occupancy -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Property-wise Occupancy</h3>
                    </div>
                    <div class="card-body">
                        @if(isset($properties) && $properties->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Property</th>
                                            <th>Total Units</th>
                                            <th>Occupied</th>
                                            <th>Vacant</th>
                                            <th>Maintenance</th>
                                            <th>Occupancy Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($properties as $property)
                                        @php
                                            $propertyOccupied = $property->tenants_count ?? 0;
                                            $propertyTotal = $property->units_count ?? 0;
                                            $propertyVacant = $propertyTotal - $propertyOccupied;
                                            $propertyRate = $propertyTotal > 0 ? ($propertyOccupied / $propertyTotal) * 100 : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $property->name }}</strong>
                                                <br><small class="text-muted">{{ $property->address }}</small>
                                            </td>
                                            <td>{{ $propertyTotal }}</td>
                                            <td class="text-success">{{ $propertyOccupied }}</td>
                                            <td class="text-warning">{{ $propertyVacant }}</td>
                                            <td class="text-secondary">{{ $property->units->where('status', 'maintenance')->count() }}</td>
                                            <td>
                                                <div class="progress progress-xs">
                                                    <div class="progress-bar bg-success" style="width: {{ $propertyRate }}%"></div>
                                                </div>
                                                <span class="badge bg-success">{{ number_format($propertyRate, 1) }}%</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No properties found for occupancy report</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Occupancy Chart -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Occupancy Distribution</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="occupancyChart" height="250"></canvas>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Statistics</h3>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Total Properties
                                <span class="badge bg-primary badge-pill">{{ $properties->count() ?? 0 }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Overall Occupancy
                                <span class="badge bg-success badge-pill">{{ number_format($occupancyRate ?? 0, 1) }}%</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Vacancy Rate
                                <span class="badge bg-warning badge-pill">
                                    @php
                                        $vacancyRate = $totalUnits > 0 ? (($vacantUnits ?? 0) / $totalUnits) * 100 : 0;
                                    @endphp
                                    {{ number_format($vacancyRate, 1) }}%
                                </span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Maintenance Rate
                                <span class="badge bg-secondary badge-pill">
                                    @php
                                        $maintenanceRate = $totalUnits > 0 ? (($maintenanceUnits ?? 0) / $totalUnits) * 100 : 0;
                                    @endphp
                                    {{ number_format($maintenanceRate, 1) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Occupancy Trend -->
        @if(isset($occupancyTrend) && count($occupancyTrend) > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Occupancy Trend (Last 6 Months)</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="occupancyTrendChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Vacant Units Details -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Vacant Units Details</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $vacantUnitsList = \App\Models\Unit::where('status', 'vacant')->with('property')->get();
                        @endphp
                        
                        @if($vacantUnitsList->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Unit Number</th>
                                            <th>Property</th>
                                            <th>Rent Amount</th>
                                            <th>Deposit</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vacantUnitsList as $unit)
                                        <tr>
                                            <td>{{ $unit->unit_number }}</td>
                                            <td>{{ $unit->property->name }}</td>
                                            <td>KSh {{ number_format($unit->rent_amount, 2) }}</td>
                                            <td>KSh {{ number_format($unit->deposit_amount, 2) }}</td>
                                            <td>
                                                <span class="badge badge-warning">Vacant</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('tenants.create') }}?unit_id={{ $unit->id }}" 
                                                   class="btn btn-success btn-sm">
                                                    <i class="fas fa-user-plus"></i> Add Tenant
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <p class="text-success">No vacant units! All units are occupied.</p>
                            </div>
                        @endif
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
        // Implement Excel export functionality
        alert('Excel export functionality would be implemented here');
    }

    $(document).ready(function() {
        // Occupancy Distribution Chart
        var occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
        var occupancyChart = new Chart(occupancyCtx, {
            type: 'doughnut',
            data: {
                labels: ['Occupied', 'Vacant', 'Maintenance'],
                datasets: [{
                    data: [
                        {{ $occupiedUnits ?? 0 }},
                        {{ $vacantUnits ?? 0 }},
                        {{ $maintenanceUnits ?? 0 }}
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#ffc107',
                        '#6c757d'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed;
                                let total = {{ $totalUnits ?? 0 }};
                                let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Occupancy Trend Chart
        @if(isset($occupancyTrend) && count($occupancyTrend) > 0)
        var trendCtx = document.getElementById('occupancyTrendChart').getContext('2d');
        var trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(collect($occupancyTrend)->pluck('month')) !!},
                datasets: [{
                    label: 'Occupied Units',
                    data: {!! json_encode(collect($occupancyTrend)->pluck('occupied')) !!},
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Occupancy Rate %',
                    data: {!! json_encode(collect($occupancyTrend)->pluck('rate')) !!},
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Occupied Units'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        max: 100,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Occupancy Rate %'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
        @endif
    });
</script>

<style>
    @media print {
        .card-tools, .btn, .breadcrumb, .info-box-icon {
            display: none !important;
        }
        .card-header {
            border-bottom: 2px solid #dee2e6 !important;
        }
        .card {
            border: 1px solid #dee2e6 !important;
        }
    }
</style>
@endsection