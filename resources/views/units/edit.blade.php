<!-- resources/views/units/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Unit')
@section('page-title', 'Edit Unit')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('units.index') }}">Units</a></li>
    <li class="breadcrumb-item active">Edit Unit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Unit Information</h3>
        <div class="card-tools">
            <a href="{{ route('units.show', $unit) }}" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i> View Unit
            </a>
        </div>
    </div>
    <form action="{{ route('units.update', $unit) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="property_id">Property *</label>
                        <select class="form-control select2 @error('property_id') is-invalid @enderror" 
                                id="property_id" name="property_id" required>
                            <option value="">Select Property</option>
                            @foreach($properties as $property)
                            <option value="{{ $property->id }}" 
                                {{ old('property_id', $unit->property_id) == $property->id ? 'selected' : '' }}>
                                {{ $property->name }} - {{ $property->address }}
                            </option>
                            @endforeach
                        </select>
                        @error('property_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="unit_number">Unit Number *</label>
                        <input type="text" class="form-control @error('unit_number') is-invalid @enderror" 
                               id="unit_number" name="unit_number" value="{{ old('unit_number', $unit->unit_number) }}" required>
                        @error('unit_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="rent_amount">Rent Amount (KSh) *</label>
                        <input type="number" step="0.01" class="form-control @error('rent_amount') is-invalid @enderror" 
                               id="rent_amount" name="rent_amount" value="{{ old('rent_amount', $unit->rent_amount) }}" required>
                        @error('rent_amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="deposit_amount">Deposit Amount (KSh) *</label>
                        <input type="number" step="0.01" class="form-control @error('deposit_amount') is-invalid @enderror" 
                               id="deposit_amount" name="deposit_amount" value="{{ old('deposit_amount', $unit->deposit_amount) }}" required>
                        @error('deposit_amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select class="form-control @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="vacant" {{ old('status', $unit->status) == 'vacant' ? 'selected' : '' }}>Vacant</option>
                            <option value="occupied" {{ old('status', $unit->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="maintenance" {{ old('status', $unit->status) == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="current_tenant">Current Tenant</label>
                        <input type="text" class="form-control" id="current_tenant" 
                               value="{{ $unit->tenant ? $unit->tenant->name : 'No tenant assigned' }}" 
                               disabled>
                        @if($unit->tenant)
                        <small class="form-text text-muted">
                            <a href="{{ route('tenants.show', $unit->tenant) }}">View Tenant Details</a>
                        </small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description', $unit->description) }}</textarea>
                @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Unit Statistics -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Unit Statistics</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-6">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon"><i class="fas fa-calendar"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Created</span>
                                            <span class="info-box-number">
                                                {{ $unit->created_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon"><i class="fas fa-sync"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Last Updated</span>
                                            <span class="info-box-number">
                                                {{ $unit->updated_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Tenant History</span>
                                            <span class="info-box-number">
                                                {{ $unit->tenants()->count() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Rent</span>
                                            <span class="info-box-number">
                                                KSh {{ number_format($unit->payments()->where('type', 'rent')->sum('amount'), 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warning for occupied units -->
            @if($unit->status == 'occupied' && $unit->tenant)
            <div class="alert alert-warning mt-3">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Warning:</strong> This unit is currently occupied by 
                <strong>{{ $unit->tenant->name }}</strong>. Changing the status may affect tenant records.
            </div>
            @endif

            <!-- Warning for maintenance units -->
            @if($unit->status == 'maintenance')
            <div class="alert alert-info mt-3">
                <i class="fas fa-tools"></i>
                <strong>Note:</strong> This unit is currently under maintenance and cannot be assigned to tenants.
            </div>
            @endif
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Unit
            </button>
            <a href="{{ route('units.index') }}" class="btn btn-default">
                <i class="fas fa-times"></i> Cancel
            </a>
            
            @if($unit->status == 'vacant')
            <a href="{{ route('tenants.create') }}?unit_id={{ $unit->id }}" class="btn btn-success float-right">
                <i class="fas fa-user-plus"></i> Assign Tenant
            </a>
            @endif

            @if($unit->status == 'occupied' && $unit->tenant)
            <a href="{{ route('payments.create') }}?tenant_id={{ $unit->tenant->id }}" class="btn btn-info float-right mr-2">
                <i class="fas fa-money-bill-wave"></i> Record Payment
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Delete Unit Modal -->
<div class="modal fade" id="deleteUnitModal" tabindex="-1" role="dialog" aria-labelledby="deleteUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUnitModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this unit?</p>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Warning:</strong> This action cannot be undone. 
                    @if($unit->tenant)
                    <br>This unit currently has a tenant assigned!
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('units.destroy', $unit) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Unit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2();

        // Auto-calculate deposit based on rent (2 months rent)
        $('#rent_amount').on('change', function() {
            const rentAmount = parseFloat($(this).val());
            if (!isNaN(rentAmount) && rentAmount > 0) {
                const depositAmount = rentAmount * 2;
                $('#deposit_amount').val(depositAmount.toFixed(2));
            }
        });

        // Status change confirmation
        $('#status').on('change', function() {
            const newStatus = $(this).val();
            const currentStatus = '{{ $unit->status }}';
            const hasTenant = {{ $unit->tenant ? 'true' : 'false' }};

            if (currentStatus === 'occupied' && newStatus !== 'occupied' && hasTenant) {
                if (!confirm('Changing status from "Occupied" will not remove the current tenant. Are you sure you want to continue?')) {
                    $(this).val(currentStatus);
                    return false;
                }
            }

            if (currentStatus === 'vacant' && newStatus === 'occupied' && !hasTenant) {
                if (confirm('Would you like to assign a tenant to this unit now?')) {
                    window.location.href = '{{ route("tenants.create") }}?unit_id={{ $unit->id }}';
                }
            }
        });

        // Property change - update unit number suggestion
        $('#property_id').on('change', function() {
            const propertyId = $(this).val();
            if (propertyId) {
                // In a real application, you might fetch the next available unit number via AJAX
                console.log('Property changed to:', propertyId);
                // You could implement AJAX call here to suggest next unit number
            }
        });
    });

    function confirmDelete() {
        $('#deleteUnitModal').modal('show');
    }
</script>

<style>
    .info-box {
        cursor: default;
    }
    .info-box .info-box-icon {
        background: rgba(0,0,0,0.1);
    }
</style>
@endsection