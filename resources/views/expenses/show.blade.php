<!-- resources/views/expenses/show.blade.php -->
@extends('layouts.app')

@section('title', 'Expense Details')
@section('page-title', 'Expense Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Expense Information</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Property:</th>
                        <td>{{ $expense->property->name }}</td>
                    </tr>
                    <tr>
                        <th>Category:</th>
                        <td>
                            <span class="badge badge-{{ $expense->category == 'maintenance' ? 'warning' : ($expense->category == 'utility' ? 'info' : 'secondary') }}">
                                {{ ucfirst($expense->category) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td>{{ $expense->description }}</td>
                    </tr>
                    <tr>
                        <th>Amount:</th>
                        <td class="text-danger font-weight-bold">KSh {{ number_format($expense->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Expense Date:</th>
                        <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Receipt Number:</th>
                        <td>{{ $expense->receipt_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Notes:</th>
                        <td>{{ $expense->notes ?? 'No additional notes' }}</td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $expense->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated:</th>
                        <td>{{ $expense->updated_at->format('M d, Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Expense
                </a>
                <a href="{{ route('expenses.index') }}" class="btn btn-default">Back to List</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Property Information</h3>
            </div>
            <div class="card-body">
                <strong>{{ $expense->property->name }}</strong>
                <p class="text-muted">{{ $expense->property->address }}</p>
                <hr>
                <p><strong>Paybill:</strong> {{ $expense->property->paybill_number }}</p>
                <p><strong>Total Units:</strong> {{ $expense->property->total_units }}</p>
                <p><strong>Occupied Units:</strong> {{ $expense->property->occupied_units }}</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-plus"></i> Add New Expense
                </a>
                <a href="{{ route('expenses.report') }}" class="btn btn-info btn-block mb-2">
                    <i class="fas fa-chart-bar"></i> View Expense Report
                </a>
                <a href="{{ route('properties.show', $expense->property) }}" class="btn btn-success btn-block">
                    <i class="fas fa-building"></i> View Property Details
                </a>
            </div>
        </div>
    </div>
</div>
@endsection