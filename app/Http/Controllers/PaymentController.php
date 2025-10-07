<?php
// app/Http/Controllers/PaymentController.php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Tenant;
use App\Models\Property;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $payments = Payment::with(['tenant', 'property'])
                ->latest()
                ->paginate(25); // Add pagination
        } else {
            $payments = Payment::whereIn('property_id', $user->properties()->pluck('id'))
                ->with(['tenant', 'property'])
                ->latest()
                ->paginate(25); // Add pagination
        }

        $properties = Property::all();

        return view('payments.index', compact('payments', 'properties'));
    }

    public function create()
    {
        $tenants = Tenant::where('status', 'active')->with(['property', 'unit'])->get();
        $properties = Property::where('is_active', true)->get();
        return view('payments.create', compact('tenants', 'properties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|exists:properties,id',
            'type' => 'required|in:rent,deposit,utility,other',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:mpesa,cash,bank,cheque',
            'status' => 'required|in:completed,pending,failed',
            'notes' => 'nullable|string',
            'receipt_number' => 'nullable|string|max:100',
        ]);

        // Generate transaction ID
        $validated['transaction_id'] = 'TXN' . time() . rand(100, 999);
        $validated['month_year'] = Carbon::parse($request->payment_date)->format('Y-m');

        $payment = Payment::create($validated);

        // Update tenant balance if it's a rent payment
        if ($request->type === 'rent') {
            $tenant = Tenant::find($request->tenant_id);
            $newBalance = max(0, $tenant->rent_balance - $request->amount);
            $tenant->update(['rent_balance' => $newBalance]);
        }

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['tenant', 'property']);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $tenants = Tenant::where('status', 'active')->with(['property', 'unit'])->get();
        $properties = Property::where('is_active', true)->get();
        return view('payments.edit', compact('payment', 'tenants', 'properties'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|exists:properties,id',
            'type' => 'required|in:rent,deposit,utility,other',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:mpesa,cash,bank,cheque',
            'status' => 'required|in:completed,pending,failed',
            'notes' => 'nullable|string',
            'receipt_number' => 'nullable|string|max:100',
        ]);

        $validated['month_year'] = Carbon::parse($request->payment_date)->format('Y-m');

        $payment->update($validated);

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}