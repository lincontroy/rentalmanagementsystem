<!-- resources/views/tenants/create.blade.php -->
@extends('layouts.app')

@section('title', 'Add Tenant')
@section('page-title', 'Add New Tenant')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tenants.index') }}">Tenants</a></li>
    <li class="breadcrumb-item active">Add Tenant</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tenant Information</h3>
    </div>
    <form action="{{ route('tenants.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="property_id">Property *</label>
                        <select class="form-control select2 @error('property_id') is-invalid @enderror" 
                                id="property_id" name="property_id" required>
                            <option value="">Select Property</option>
                            @foreach($properties as $property)
                            <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
                                {{ $property->name }}
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
                        <label for="unit_id">Unit *</label>
                        <select class="form-control select2 @error('unit_id') is-invalid @enderror" 
                                id="unit_id" name="unit_id" required>
                            <option value="">Select Unit</option>
                            @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->unit_number }} - KSh {{ number_format($unit->rent_amount, 2) }}
                            </option>
                            @endforeach
                        </select>
                        @error('unit_id')
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
                        <label for="name">Full Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
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
                        <label for="phone">Phone Number *</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_number">ID Number</label>
                        <input type="text" class="form-control @error('id_number') is-invalid @enderror" 
                               id="id_number" name="id_number" value="{{ old('id_number') }}">
                        @error('id_number')
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
                        <label for="lease_start_date">Lease Start Date *</label>
                        <input type="date" class="form-control @error('lease_start_date') is-invalid @enderror" 
                               id="lease_start_date" name="lease_start_date" value="{{ old('lease_start_date') }}" required>
                        @error('lease_start_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lease_end_date">Lease End Date</label>
                        <input type="date" class="form-control @error('lease_end_date') is-invalid @enderror" 
                               id="lease_end_date" name="lease_end_date" value="{{ old('lease_end_date') }}">
                        @error('lease_end_date')
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
                        <label for="rent_balance">Initial Rent Balance</label>
                        <input type="number" step="0.01" class="form-control @error('rent_balance') is-invalid @enderror" 
                               id="rent_balance" name="rent_balance" value="{{ old('rent_balance', 0) }}">
                        @error('rent_balance')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="deposit_balance">Deposit Balance</label>
                        <input type="number" step="0.01" class="form-control @error('deposit_balance') is-invalid @enderror" 
                               id="deposit_balance" name="deposit_balance" value="{{ old('deposit_balance', 0) }}">
                        @error('deposit_balance')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="emergency_contact">Emergency Contact</label>
                <textarea class="form-control @error('emergency_contact') is-invalid @enderror" 
                          id="emergency_contact" name="emergency_contact" rows="2">{{ old('emergency_contact') }}</textarea>
                @error('emergency_contact')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Tenant</button>
            <a href="{{ route('tenants.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        // Update units based on selected property
        $('#property_id').change(function() {
            var propertyId = $(this).val();
            if (propertyId) {
                $.ajax({
                    url: '/get-units/' + propertyId,
                    type: 'GET',
                    success: function(data) {
                        $('#unit_id').empty();
                        $('#unit_id').append('<option value="">Select Unit</option>');
                        $.each(data, function(key, unit) {
                            $('#unit_id').append('<option value="'+ unit.id +'">'+ unit.unit_number + ' - KSh ' + unit.rent_amount +'</option>');
                        });
                    }
                });
            } else {
                $('#unit_id').empty();
                $('#unit_id').append('<option value="">Select Unit</option>');
            }
        });
    });
</script>
@endsection
@endsection