<?php
// app/Http/Controllers/UnitController.php
namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with(['property', 'tenant'])->get();
        return view('units.index', compact('units'));
    }

    public function create()
    {
        $properties = Property::where('is_active', true)->get();
        return view('units.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_number' => 'required|string|max:50',
            'rent_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'status' => 'required|in:occupied,vacant,maintenance',
            'description' => 'nullable|string',
        ]);

        Unit::create($validated);

        // Update property total units count
        $property = Property::find($request->property_id);
        $property->increment('total_units');

        return redirect()->route('units.index')
            ->with('success', 'Unit created successfully.');
    }

    public function show(Unit $unit)
    {
        $unit->load(['property', 'tenant']);
        return view('units.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        $user = Auth::user();
        
      
        // Check if user can edit this unit
        if (!$user->isAdmin() && !in_array($unit->property_id, $user->properties()->pluck('id')->toArray())) {
            abort(403, 'Unauthorized access to this unit.');
        }

        if ($user->isAdmin()) {
            $properties = Property::where('is_active', true)->get();
        } else {
            $properties = $user->properties()->where('is_active', true)->get();
        }

        // Load tenant relationship for display
        $unit->load('tenant');

        return view('units.edit', compact('unit', 'properties'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_number' => 'required|string|max:50',
            'rent_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'status' => 'required|in:occupied,vacant,maintenance',
            'description' => 'nullable|string',
        ]);

        $unit->update($validated);

        return redirect()->route('units.index')
            ->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        // Update property total units count
        $property = $unit->property;
        $property->decrement('total_units');

        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'Unit deleted successfully.');
    }
}