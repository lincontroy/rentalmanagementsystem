<!-- resources/views/properties/show.blade.php -->
@extends('layouts.app')

@section('title', $property->name)
@section('page-title', $property->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('properties.index') }}">Properties</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Property Info Card -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Property Information</h3>
            </div>
            <div class="card-body">
                <strong><i class="fas fa-building mr-1"></i> Property Name</strong>
                <p class="text-muted">{{ $property->name }}</p>
                <hr>

                <strong><i class="fas fa-receipt mr-1"></i> Paybill Number</strong>
                <p class="text-muted">{{ $property->paybill_number }}</p>
                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                <p class="text-muted">{{ $property->address }}</p>
                <hr>

                <strong><i class="fas fa-home mr-1"></i> Units Information</strong>
                <p class="text-muted">
                    Total Units: {{ $property->total_units }}<br>
                    Occupied: {{ $property->occupied_units }}<br>
                    Vacant: {{ $property->total_units - $property->occupied_units }}
                </p>
                <hr>

                <strong><i class="fas fa-chart-line mr-1"></i> Occupancy Rate</strong>
                <div class="progress mb-3">
                    <div class="progress-bar bg-success" style="width: {{ $property->occupancy_rate }}%">
                        {{ number_format($property->occupancy_rate, 1) }}%
                    </div>
                </div>

                <strong><i class="fas fa-money-bill-wave mr-1"></i> Monthly Rent Total</strong>
                <p class="text-muted">KSh {{ number_format($property->monthly_rent_total, 2) }}</p>
                <hr>

                <strong><i class="fas fa-info-circle mr-1"></i> Status</strong>
                <p class="text-muted">
                    <span class="badge badge-{{ $property->is_active ? 'success' : 'danger' }}">
                        {{ $property->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Units Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Units ({{ $property->units->count() }})</h3>
                <div class="card-tools">
                    <a href="{{ route('units.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Unit
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Unit Number</th>
                            <th>Rent Amount</th>
                            <th>Deposit</th>
                            <th>Status</th>
                            <th>Tenant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($property->units as $unit)
                        <tr>
                            <td>{{ $unit->unit_number }}</td>
                            <td>KSh {{ number_format($unit->rent_amount, 2) }}</td>
                            <td>KSh {{ number_format($unit->deposit_amount, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $unit->status == 'occupied' ? 'success' : ($unit->status == 'vacant' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($unit->status) }}
                                </span>
                            </td>
                            <td>
                                @if($unit->tenant)
                                    {{ $unit->tenant->name }}
                                @else
                                    <span class="text-muted">No Tenant</span>
                                @endif
                            </td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="#" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Payments Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Payments</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Tenant</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($property->payments->take(10) as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td>{{ $payment->tenant->name }}</td>
                            <td>
                                <span class="badge badge-{{ $payment->type == 'rent' ? 'primary' : 'success' }}">
                                    {{ ucfirst($payment->type) }}
                                </span>
                            </td>
                            <td>KSh {{ number_format($payment->amount, 2) }}</td>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection