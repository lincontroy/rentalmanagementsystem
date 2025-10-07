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

        <div class="table-responsive">
            <table class="table table-bordered table-striped datatable">
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
                        <td>KSh {{ number_format($property->monthly_rent_total, 2) }}</td>
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
    </div>
</div>
@endsection