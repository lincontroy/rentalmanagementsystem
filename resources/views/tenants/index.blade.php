<!-- resources/views/tenants/index.blade.php -->
@extends('layouts.app')

@section('title', 'Tenants')
@section('page-title', 'Tenants Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Tenants</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Tenants</h3>
        <div class="card-tools">
            <a href="{{ route('tenants.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Tenant
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
                        <th>Tenant Name</th>
                        <th>Property</th>
                        <th>Unit</th>
                        <th>Contact</th>
                        <th>Rent Balance</th>
                        <th>Lease Period</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tenants as $tenant)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $tenant->name }}</strong>
                            <br><small class="text-muted">ID: {{ $tenant->id_number ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $tenant->property->name }}</td>
                        <td>{{ $tenant->unit->unit_number }}</td>
                        <td>
                            <small>
                                <i class="fas fa-phone"></i> {{ $tenant->phone }}<br>
                                <i class="fas fa-envelope"></i> {{ $tenant->email }}
                            </small>
                        </td>
                        <td>
                            <span class="badge badge-{{ $tenant->rent_balance > 0 ? 'danger' : 'success' }}">
                                KSh {{ number_format($tenant->rent_balance, 2) }}
                            </span>
                        </td>
                        <td>
                            <small>
                                Start: {{ $tenant->lease_start_date->format('M d, Y') }}<br>
                                @if($tenant->lease_end_date)
                                End: {{ $tenant->lease_end_date->format('M d, Y') }}
                                @else
                                End: Not set
                                @endif
                            </small>
                        </td>
                        <td>
                            <span class="badge badge-{{ $tenant->status == 'active' ? 'success' : ($tenant->status == 'inactive' ? 'warning' : 'danger') }}">
                                {{ ucfirst($tenant->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('tenants.show', $tenant) }}" class="btn btn-info btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('tenants.edit', $tenant) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" style="display: inline;">
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