<!-- resources/views/properties/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Property')
@section('page-title', 'Edit Property')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('properties.index') }}">Properties</a></li>
    <li class="breadcrumb-item active">Edit Property</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Property Information</h3>
    </div>
    <form action="{{ route('properties.update', $property) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Property Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $property->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="paybill_number">Paybill Number *</label>
                        <input type="text" class="form-control @error('paybill_number') is-invalid @enderror" 
                               id="paybill_number" name="paybill_number" value="{{ old('paybill_number', $property->paybill_number) }}" required>
                        @error('paybill_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="address">Property Address *</label>
                <textarea class="form-control @error('address') is-invalid @enderror" 
                          id="address" name="address" rows="3" required>{{ old('address', $property->address) }}</textarea>
                @error('address')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="total_units">Total Units *</label>
                        <input type="number" class="form-control @error('total_units') is-invalid @enderror" 
                               id="total_units" name="total_units" value="{{ old('total_units', $property->total_units) }}" min="1" required>
                        @error('total_units')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="monthly_rent_total">Estimated Monthly Rent Total (KSh)</label>
                        <input type="number" step="0.01" class="form-control @error('monthly_rent_total') is-invalid @enderror" 
                               id="monthly_rent_total" name="monthly_rent_total" value="{{ old('monthly_rent_total', $property->monthly_rent_total) }}">
                        @error('monthly_rent_total')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            @if(Auth::user()->isAdmin())
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" 
                           {{ $property->is_active ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">Active Property</label>
                </div>
            </div>
            @endif
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Property</button>
            <a href="{{ route('properties.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection