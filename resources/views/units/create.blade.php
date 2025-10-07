<!-- resources/views/units/create.blade.php -->
@extends('layouts.app')

@section('title', 'Add Unit')
@section('page-title', 'Add New Unit')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('units.index') }}">Units</a></li>
    <li class="breadcrumb-item active">Add Unit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Unit Information</h3>
    </div>
    <form action="{{ route('units.store') }}" method="POST">
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
                        <label for="unit_number">Unit Number *</label>
                        <input type="text" class="form-control @error('unit_number') is-invalid @enderror" 
                               id="unit_number" name="unit_number" value="{{ old('unit_number') }}" required>
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
                               id="rent_amount" name="rent_amount" value="{{ old('rent_amount') }}" required>
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
                               id="deposit_amount" name="deposit_amount" value="{{ old('deposit_amount') }}" required>
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
                            <option value="vacant" {{ old('status') == 'vacant' ? 'selected' : '' }}>Vacant</option>
                            <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Unit</button>
            <a href="{{ route('units.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection