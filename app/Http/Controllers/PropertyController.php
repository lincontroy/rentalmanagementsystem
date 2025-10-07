<?php
// app/Http/Controllers/PropertyController.php
namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $properties = Property::withCount(['tenants', 'units'])->get();
        } else {
            $properties = Property::where('user_id', $user->id)
                ->withCount(['tenants', 'units'])
                ->get();
        }
        
        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'paybill_number' => 'required|string|unique:properties',
            'address' => 'required|string',
            'total_units' => 'required|integer|min:1',
            'monthly_rent_total' => 'nullable|numeric|min:0',
        ]);

        // Add user_id to the validated data
        $validated['user_id'] = $user->id;
        $validated['occupied_units'] = 0;
        $validated['is_active'] = true;

        // Set default monthly rent total if not provided
        if (!isset($validated['monthly_rent_total'])) {
            $validated['monthly_rent_total'] = 0;
        }

        // dd($validated);

        $property = Property::create($validated);

        return redirect()->route('properties.index')
            ->with('success', 'Property created successfully.');
    }

    public function show(Property $property)
    {
        // Check if user is authorized to view this property
        $user = Auth::user();
        if (!$user->isAdmin() && $property->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this property.');
        }

        $property->load(['units', 'tenants' => function($query) {
            $query->where('status', 'active');
        }, 'payments' => function($query) {
            $query->orderBy('created_at', 'desc')->take(10);
        }]);

        // Calculate statistics
        $totalRentCollected = $property->payments()
            ->where('type', 'rent')
            ->where('status', 'completed')
            ->sum('amount');

        $currentMonthRevenue = $property->payments()
            ->where('type', 'rent')
            ->where('status', 'completed')
            ->where('month_year', now()->format('Y-m'))
            ->sum('amount');

        return view('properties.show', compact('property', 'totalRentCollected', 'currentMonthRevenue'));
    }

    public function edit(Property $property)
    {
        // Check if user is authorized to edit this property
        $user = Auth::user();
        if (!$user->isAdmin() && $property->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this property.');
        }

        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        // Check if user is authorized to update this property
        $user = Auth::user();
        if (!$user->isAdmin() && $property->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this property.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'paybill_number' => 'required|string|unique:properties,paybill_number,' . $property->id,
            'address' => 'required|string',
            'total_units' => 'required|integer|min:1',
            'monthly_rent_total' => 'nullable|numeric|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $property->update($validated);

        return redirect()->route('properties.index')
            ->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        // Check if user is authorized to delete this property
        $user = Auth::user();
        if (!$user->isAdmin() && $property->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this property.');
        }

        // Check if property has tenants before deleting
        if ($property->tenants()->where('status', 'active')->exists()) {
            return redirect()->route('properties.index')
                ->with('error', 'Cannot delete property with active tenants. Please transfer or remove tenants first.');
        }

        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'Property deleted successfully.');
    }

    public function toggleStatus(Property $property)
    {
        // Check if user is authorized to update this property
        $user = Auth::user();
        if (!$user->isAdmin() && $property->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this property.');
        }

        $property->update(['is_active' => !$property->is_active]);

        $status = $property->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('properties.index')
            ->with('success', "Property {$status} successfully.");
    }

    public function statistics(Property $property)
    {
        // Check if user is authorized to view this property
        $user = Auth::user();
        if (!$user->isAdmin() && $property->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this property.');
        }

        $statistics = [
            'total_units' => $property->units_count,
            'occupied_units' => $property->tenants_count,
            'vacant_units' => $property->units_count - $property->tenants_count,
            'occupancy_rate' => $property->units_count > 0 ? ($property->tenants_count / $property->units_count) * 100 : 0,
            'total_rent_collected' => $property->payments()->where('type', 'rent')->where('status', 'completed')->sum('amount'),
            'current_month_revenue' => $property->payments()
                ->where('type', 'rent')
                ->where('status', 'completed')
                ->where('month_year', now()->format('Y-m'))
                ->sum('amount'),
            'total_arrears' => $property->tenants()->sum('rent_balance'),
        ];

        return response()->json($statistics);
    }
}