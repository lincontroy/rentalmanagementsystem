<!-- resources/views/expenses/index.blade.php -->
@extends('layouts.app')

@section('title', 'Expenses')
@section('page-title', 'Expenses Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Expenses</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Expenses</h3>
        <div class="card-tools">
            <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Expense
            </a>
            <a href="{{ route('expenses.report') }}" class="btn btn-info btn-sm">
                <i class="fas fa-chart-bar"></i> Expense Report
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

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="fas fa-money-bill-wave"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Expenses</span>
                        <span class="info-box-number">KSh {{ number_format($totalExpenses, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-calendar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">This Month</span>
                        <span class="info-box-number">KSh {{ number_format($monthlyExpenses, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-receipt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Records</span>
                        <span class="info-box-number">{{ $expenses->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Property</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Receipt No.</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                        <td>{{ $expense->property->name }}</td>
                        <td>
                            <span class="badge badge-{{ $expense->category == 'maintenance' ? 'warning' : ($expense->category == 'utility' ? 'info' : 'secondary') }}">
                                {{ ucfirst($expense->category) }}
                            </span>
                        </td>
                        <td>{{ Str::limit($expense->description, 50) }}</td>
                        <td class="text-danger">KSh {{ number_format($expense->amount, 2) }}</td>
                        <td>{{ $expense->receipt_number ?? 'N/A' }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('expenses.show', $expense) }}" class="btn btn-info btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" style="display: inline;">
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
                <tfoot>
                    <tr class="bg-light">
                        <th colspan="5" class="text-right">Total:</th>
                        <th class="text-danger">KSh {{ number_format($expenses->sum('amount'), 2) }}</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection