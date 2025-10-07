<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Tenant;
use App\Models\Payment;
use App\Models\Unit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Strong authentication check
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access the dashboard.');
        }

        $user = Auth::user();

        // Initialize all variables with default values
        $totalProperties = 0;
        $totalTenants = 0;
        $totalUnits = 0;
        $occupiedUnits = 0;
        $monthlyRevenue = 0;
        $properties = collect();
        $recentPayments = collect();
        $collectionRate = 0;
        $vacantUnits = 0;
        $totalArrears = 0;

        try {
            // Base query based on user role
            if ($user->isAdmin()) {
                $propertiesQuery = Property::query();
                $tenantsQuery = Tenant::query();
                $paymentsQuery = Payment::query();
                $unitsQuery = Unit::query();
            } else {
                // For managers, only show their assigned properties
                $propertiesQuery = Property::where('user_id', $user->id);
                $tenantsQuery = Tenant::whereIn('property_id', $user->properties()->pluck('id'));
                $paymentsQuery = Payment::whereIn('property_id', $user->properties()->pluck('id'));
                $unitsQuery = Unit::whereIn('property_id', $user->properties()->pluck('id'));
            }

            $totalProperties = $propertiesQuery->count();
            $totalTenants = $tenantsQuery->where('status', 'active')->count();
            $totalUnits = $unitsQuery->count();
            $occupiedUnits = $unitsQuery->where('status', 'occupied')->count();
            
            // Current month revenue
            $currentMonth = Carbon::now()->format('Y-m');
            $monthlyRevenue = $paymentsQuery->clone()
                ->where('month_year', $currentMonth)
                ->where('status', 'completed')
                ->sum('amount') ?? 0;

            // Properties with their stats
            $properties = $propertiesQuery->withCount([
                'tenants as active_tenants_count' => function($query) {
                    $query->where('status', 'active');
                },
                'units'
            ])->get();

            // Recent payments with proper relationship loading
            $recentPayments = $paymentsQuery->clone()
                ->with(['tenant', 'property'])
                ->where('status', 'completed')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            // Rent collection rate
            $totalMonthlyRent = $propertiesQuery->clone()->sum('monthly_rent_total') ?? 0;
            $collectionRate = $totalMonthlyRent > 0 ? 
                ($monthlyRevenue / $totalMonthlyRent) * 100 : 0;

            // Additional stats
            $vacantUnits = $totalUnits - $occupiedUnits;
            $totalArrears = $tenantsQuery->clone()->where('status', 'active')->sum('rent_balance') ?? 0;

        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            // All variables already have default values, so we can continue
        }

        // Pass all variables to the view
        return view('dashboard', compact(
            'totalProperties', 'totalTenants', 'totalUnits', 
            'occupiedUnits', 'monthlyRevenue', 'properties',
            'recentPayments', 'collectionRate', 'vacantUnits', 'totalArrears', 'user'
        ));
    }
}