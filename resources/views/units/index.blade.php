<!-- resources/views/units/index.blade.php -->
@extends('layouts.app')

@section('title', 'Units')
@section('page-title', 'Units Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Units</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Units</h3>
        <div class="card-tools">
            <a href="{{ route('units.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Unit
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
                        <th>Unit Number</th>
                        <th>Property</th>
                        <th>Rent Amount</th>
                        <th>Deposit</th>
                        <th>Status</th>
                        <th>Tenant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($units as $unit)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $unit->unit_number }}</td>
                        <td>{{ $unit->property->name }}</td>
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
                            <div class="btn-group">
                                <a href="{{ route('units.show', $unit) }}" class="btn btn-info btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('units.edit', $unit) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('units.destroy', $unit) }}" method="POST" style="display: inline;">
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