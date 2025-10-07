<?php
// app/Http/Controllers/ExpenseController.php
namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index()
    {
        // Check authorization
        $user = Auth::user();
       

        if ($user->isAdmin()) {
            $expenses = Expense::with('property')->latest()->get();
        } else {
            $expenses = Expense::whereIn('property_id', $user->properties()->pluck('id'))
                ->with('property')
                ->latest()
                ->get();
        }

        $totalExpenses = $expenses->sum('amount');
        $monthlyExpenses = Expense::whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        return view('expenses.index', compact('expenses', 'totalExpenses', 'monthlyExpenses'));
    }

    public function create()
    {
        // Check authorization
        $user = Auth::user();
       

        if ($user->isAdmin()) {
            $properties = Property::where('is_active', true)->get();
        } else {
            $properties = $user->properties()->where('is_active', true)->get();
        }

        $categories = [
            'maintenance' => 'Maintenance & Repairs',
            'utility' => 'Utilities (Water, Electricity)',
            'staff' => 'Staff Salaries',
            'insurance' => 'Insurance',
            'tax' => 'Taxes',
            'security' => 'Security',
            'cleaning' => 'Cleaning Services',
            'management' => 'Management Fees',
            'other' => 'Other Expenses'
        ];

        return view('expenses.create', compact('properties', 'categories'));
    }

    public function store(Request $request)
    {
        // Check authorization
        $user = Auth::user();
   

        // Validate that the property belongs to the user if not admin
        if (!$user->isAdmin()) {
            $userPropertyIds = $user->properties()->pluck('id')->toArray();
            if (!in_array($request->property_id, $userPropertyIds)) {
                abort(403, 'Unauthorized access to this property.');
            }
        }

        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'category' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'receipt_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    public function show(Expense $expense)
    {
        // Check authorization
        $user = Auth::user();
     

        // Check if user can view this expense
        if (!$user->isAdmin() && !in_array($expense->property_id, $user->properties()->pluck('id')->toArray())) {
            abort(403, 'Unauthorized access to this expense.');
        }

        $expense->load('property');
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        // Check authorization
        $user = Auth::user();
      

        // Check if user can edit this expense
        if (!$user->isAdmin() && !in_array($expense->property_id, $user->properties()->pluck('id')->toArray())) {
            abort(403, 'Unauthorized access to this expense.');
        }

        if ($user->isAdmin()) {
            $properties = Property::where('is_active', true)->get();
        } else {
            $properties = $user->properties()->where('is_active', true)->get();
        }

        $categories = [
            'maintenance' => 'Maintenance & Repairs',
            'utility' => 'Utilities (Water, Electricity)',
            'staff' => 'Staff Salaries',
            'insurance' => 'Insurance',
            'tax' => 'Taxes',
            'security' => 'Security',
            'cleaning' => 'Cleaning Services',
            'management' => 'Management Fees',
            'other' => 'Other Expenses'
        ];

        return view('expenses.edit', compact('expense', 'properties', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        // Check authorization
        $user = Auth::user();
       

        // Check if user can update this expense
        if (!$user->isAdmin() && !in_array($expense->property_id, $user->properties()->pluck('id')->toArray())) {
            abort(403, 'Unauthorized access to this expense.');
        }

        // Validate that the new property belongs to the user if not admin
        if (!$user->isAdmin() && $request->property_id != $expense->property_id) {
            $userPropertyIds = $user->properties()->pluck('id')->toArray();
            if (!in_array($request->property_id, $userPropertyIds)) {
                abort(403, 'Unauthorized access to this property.');
            }
        }

        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'category' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'receipt_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        // Check authorization
        $user = Auth::user();
       

        // Check if user can delete this expense
        if (!$user->isAdmin() && !in_array($expense->property_id, $user->properties()->pluck('id')->toArray())) {
            abort(403, 'Unauthorized access to this expense.');
        }

        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    public function report(Request $request)
    {
        // Check authorization
        $user = Auth::user();
       

        $query = Expense::query();
        
        if (!$user->isAdmin()) {
            $query->whereIn('property_id', $user->properties()->pluck('id'));
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        } else {
            // Default to current month
            $query->whereYear('expense_date', now()->year)
                  ->whereMonth('expense_date', now()->month);
        }

        // Filter by property
        if ($request->property_id) {
            $query->where('property_id', $request->property_id);
        }

        // Filter by category
        if ($request->category) {
            $query->where('category', $request->category);
        }

        $expenses = $query->with('property')->get();
        $totalAmount = $expenses->sum('amount');

        $categories = [
            'maintenance' => 'Maintenance & Repairs',
            'utility' => 'Utilities',
            'staff' => 'Staff Salaries',
            'insurance' => 'Insurance',
            'tax' => 'Taxes',
            'security' => 'Security',
            'cleaning' => 'Cleaning',
            'management' => 'Management',
            'other' => 'Other'
        ];

        $properties = $user->isAdmin() ? Property::all() : $user->properties;

        return view('expenses.report', compact('expenses', 'totalAmount', 'categories', 'properties'));
    }

    public function export(Request $request)
    {
        // Check authorization
        $user = Auth::user();
       

        $query = Expense::query();
        
        if (!$user->isAdmin()) {
            $query->whereIn('property_id', $user->properties()->pluck('id'));
        }

        // Apply filters
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        }

        if ($request->property_id) {
            $query->where('property_id', $request->property_id);
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $expenses = $query->with('property')->get();

        // In a real application, you would generate Excel/PDF here
        // For now, we'll return a JSON response
        return response()->json([
            'message' => 'Export functionality would be implemented here',
            'expenses_count' => $expenses->count(),
            'total_amount' => $expenses->sum('amount')
        ]);
    }

    public function summary()
    {
        // Check authorization
        $user = Auth::user();
       

        $query = Expense::query();
        
        if (!$user->isAdmin()) {
            $query->whereIn('property_id', $user->properties()->pluck('id'));
        }

        // Current month summary
        $currentMonthExpenses = $query->clone()
            ->whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        // Previous month summary
        $previousMonthExpenses = $query->clone()
            ->whereYear('expense_date', now()->subMonth()->year)
            ->whereMonth('expense_date', now()->subMonth()->month)
            ->sum('amount');

        // Category-wise breakdown for current month
        $categoryBreakdown = $query->clone()
            ->whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        // Property-wise breakdown for current month
        $propertyBreakdown = $query->clone()
            ->whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->with('property')
            ->selectRaw('property_id, SUM(amount) as total')
            ->groupBy('property_id')
            ->get();

        return response()->json([
            'current_month_total' => $currentMonthExpenses,
            'previous_month_total' => $previousMonthExpenses,
            'category_breakdown' => $categoryBreakdown,
            'property_breakdown' => $propertyBreakdown,
            'change_percentage' => $previousMonthExpenses > 0 ? 
                (($currentMonthExpenses - $previousMonthExpenses) / $previousMonthExpenses) * 100 : 0
        ]);
    }
}