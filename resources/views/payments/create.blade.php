<!-- resources/views/payments/create.blade.php -->
@extends('layouts.app')

@section('title', 'Record Payment')
@section('page-title', 'Record New Payment')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Payments</a></li>
    <li class="breadcrumb-item active">Record Payment</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Payment Information</h3>
    </div>
    <form action="{{ route('payments.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tenant_id">Tenant *</label>
                        <select class="form-control select2 @error('tenant_id') is-invalid @enderror" 
                                id="tenant_id" name="tenant_id" required>
                            <option value="">Select Tenant</option>
                            @foreach($tenants as $tenant)
                            <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                {{ $tenant->name }} - {{ $tenant->property->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('tenant_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
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
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type">Payment Type *</label>
                        <select class="form-control @error('type') is-invalid @enderror" 
                                id="type" name="type" required>
                            <option value="rent" {{ old('type') == 'rent' ? 'selected' : '' }}>Rent</option>
                            <option value="deposit" {{ old('type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                            <option value="utility" {{ old('type') == 'utility' ? 'selected' : '' }}>Utility</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="amount">Amount (KSh) *</label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                               id="amount" name="amount" value="{{ old('amount') }}" required>
                        @error('amount')
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
                        <label for="payment_date">Payment Date *</label>
                        <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                               id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                        @error('payment_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_method">Payment Method *</label>
                        <select class="form-control @error('payment_method') is-invalid @enderror" 
                                id="payment_method" name="payment_method" required>
                            <option value="mpesa" {{ old('payment_method') == 'mpesa' ? 'selected' : '' }}>M-Pesa</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                        @error('payment_method')
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
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
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
                        <label for="receipt_number">Receipt Number</label>
                        <input type="text" class="form-control @error('receipt_number') is-invalid @enderror" 
                               id="receipt_number" name="receipt_number" value="{{ old('receipt_number') }}">
                        @error('receipt_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" 
                          id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Record Payment</button>
            <a href="{{ route('payments.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection