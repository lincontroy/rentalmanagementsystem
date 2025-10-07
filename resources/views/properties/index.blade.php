<!-- resources/views/properties/index.blade.php -->
@extends('layouts.app')

@section('title', 'Properties')
@section('page-title', 'Properties Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Properties</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Properties</h3>
        <div class="card-tools">
            <a href="{{ route('properties.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Property
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

        <!-- Desktop Table View (hidden on mobile) -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Property Name</th>
                        <th>Paybill Number</th>
                        <th>Total Units</th>
                        <th>Occupied Units</th>
                        <th>Occupancy Rate</th>
                        <th>Monthly Rent Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($properties as $property)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $property->name }}</strong>
                            <br><small class="text-muted">{{ $property->address }}</small>
                        </td>
                        <td>{{ $property->paybill_number }}</td>
                        <td>{{ $property->total_units }}</td>
                        <td>{{ $property->occupied_units }}</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-success" style="width: {{ $property->occupancy_rate }}%"></div>
                            </div>
                            <span class="badge bg-success">{{ number_format($property->occupancy_rate, 1) }}%</span>
                        </td>
                        <td><span class="money-figure">KSh {{ number_format($property->monthly_rent_total, 0) }}</span></td>
                        <td>
                            <span class="badge badge-{{ $property->is_active ? 'success' : 'danger' }}">
                                {{ $property->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('properties.show', $property) }}" class="btn btn-info btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('properties.edit', $property) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('properties.destroy', $property) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View (visible only on mobile) -->
        <div class="d-md-none">
            @if($properties->count() > 0)
                <div class="row">
                    @foreach($properties as $property)
                    <div class="col-12 mb-3">
                        <div class="card property-card">
                            <div class="card-body">
                                <!-- Property Header -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0 text-primary">
                                        {{ $property->name }}
                                    </h6>
                                    <span class="badge badge-{{ $property->is_active ? 'success' : 'danger' }}">
                                        {{ $property->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                
                                <!-- Property Details -->
                                <p class="card-text text-muted small mb-2">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ Str::limit($property->address, 50) }}
                                </p>
                                
                                <p class="card-text small mb-2">
                                    <i class="fas fa-receipt mr-1"></i>
                                    Paybill: <strong>{{ $property->paybill_number }}</strong>
                                </p>

                                <!-- Units Information -->
                                <div class="row text-center mb-2">
                                    <div class="col-4">
                                        <div class="border-right">
                                            <small class="text-muted d-block">Total Units</small>
                                            <strong class="text-primary">{{ $property->total_units }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-right">
                                            <small class="text-muted d-block">Occupied</small>
                                            <strong class="text-success">{{ $property->occupied_units }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Vacant</small>
                                        <strong class="text-warning">{{ $property->total_units - $property->occupied_units }}</strong>
                                    </div>
                                </div>

                                <!-- Occupancy Progress -->
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-muted">Occupancy Rate</small>
                                        <small class="font-weight-bold text-success">{{ number_format($property->occupancy_rate, 1) }}%</small>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: {{ $property->occupancy_rate }}%"></div>
                                    </div>
                                </div>

                                <!-- Financial Information -->
                                <div class="row text-center mb-3">
                                    <div class="col-12">
                                        <small class="text-muted d-block">Monthly Rent Potential</small>
                                        <strong class="money-figure text-dark">KSh {{ number_format($property->monthly_rent_total, 0) }}</strong>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('properties.show', $property) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('properties.edit', $property) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="{{ route('units.index') }}?property_id={{ $property->id }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-home"></i> Units
                                    </a>
                                    <form action="{{ route('properties.destroy', $property) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this property?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Properties Found</h5>
                    <p class="text-muted">Get started by adding your first property</p>
                    <a href="{{ route('properties.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add First Property
                    </a>
                </div>
            @endif
        </div>

        <!-- Pagination for both views -->
        @if($properties->hasPages())
        <div class="row mt-3">
            <div class="col-md-6">
                <p class="text-muted">
                    Showing {{ $properties->firstItem() }} to {{ $properties->lastItem() }} of {{ $properties->total() }} entries
                </p>
            </div>
            <div class="col-md-6">
                <div class="float-right">
                    {{ $properties->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // You can add any JavaScript functionality here if needed
    document.addEventListener('DOMContentLoaded', function() {
        // Add any interactive functionality for mobile view
    });
</script>

<style>
    /* Property card styling for mobile */
    .property-card {
        border-left: 4px solid #007bff;
        transition: all 0.3s ease;
        border-radius: 8px;
    }
    
    .property-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .property-card .card-body {
        padding: 15px;
    }
    
    .property-card .card-title {
        font-size: 1rem;
        font-weight: 600;
    }
    
    .property-card .card-text {
        font-size: 0.85rem;
        margin-bottom: 8px;
    }
    
    .property-card .progress {
        border-radius: 3px;
    }
    
    /* Money figure styling */
    .money-figure {
        font-size: 0.9em;
        font-weight: 600;
    }
    
    /* Border utilities for mobile layout */
    .border-right {
        border-right: 1px solid #dee2e6 !important;
    }
    
    /* Button styling for mobile */
    .property-card .btn-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* Responsive text sizes */
    @media (max-width: 576px) {
        .property-card .card-title {
            font-size: 0.95rem;
        }
        
        .property-card .card-text {
            font-size: 0.8rem;
        }
        
        .money-figure {
            font-size: 0.85rem;
        }
    }
    
    /* Empty state styling */
    .text-center.py-5 {
        padding: 3rem 1rem;
    }
</style>
@endsection