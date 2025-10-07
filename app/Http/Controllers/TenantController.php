<?php
// app/Http/Controllers/TenantController.php
namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with(['property', 'unit'])->get();
        return view('tenants.index', compact('tenants'));
    }

    public function create()
    {
        $properties = Property::where('is_active', true)->get();
        $units = Unit::where('status', 'vacant')->get();
        return view('tenants.create', compact('properties', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants',
            'phone' => 'required|string|max:20',
            'id_number' => 'nullable|string|max:50',
            'lease_start_date' => 'required|date',
            'lease_end_date' => 'nullable|date|after:lease_start_date',
            'rent_balance' => 'nullable|numeric|min:0',
            'deposit_balance' => 'nullable|numeric|min:0',
            'emergency_contact' => 'nullable|string',
        ]);

        // Update unit status to occupied
        $unit = Unit::find($request->unit_id);
        $unit->update(['status' => 'occupied']);

        // Update property occupied units count
        $property = Property::find($request->property_id);
        $property->increment('occupied_units');

        Tenant::create($validated);

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant created successfully.');
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['property', 'unit', 'payments']);
        return view('tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        $properties = Property::where('is_active', true)->get();
        $units = Unit::all();
        return view('tenants.edit', compact('tenant', 'properties', 'units'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email,' . $tenant->id,
            'phone' => 'required|string|max:20',
            'id_number' => 'nullable|string|max:50',
            'lease_start_date' => 'required|date',
            'lease_end_date' => 'nullable|date|after:lease_start_date',
            'rent_balance' => 'nullable|numeric|min:0',
            'deposit_balance' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,evicted',
            'emergency_contact' => 'nullable|string',
        ]);

        $tenant->update($validated);

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant updated successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        // Update unit status to vacant
        $unit = $tenant->unit;
        $unit->update(['status' => 'vacant']);

        // Update property occupied units count
        $property = $tenant->property;
        $property->decrement('occupied_units');

        $tenant->delete();

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant deleted successfully.');
    }
}