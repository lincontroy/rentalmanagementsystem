<?php
// app/Http/Controllers/ReportController.php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Tenant;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class ReportController extends Controller
{
    public function rentReport(Request $request)
    {
        $date = $request->get('month', date('Y-m'));
        
        $rentPayments = Payment::where('type', 'rent')
            ->where('month_year', $date)
            ->with(['tenant', 'property'])
            ->get();

        $totalRent = $rentPayments->where('status', 'completed')->sum('amount');
        $totalTransactions = $rentPayments->count();
        $pendingRent = $rentPayments->where('status', 'pending')->sum('amount');
        
        // Calculate collection rate
        $totalExpectedRent = Property::sum('monthly_rent_total');
        $collectionRate = $totalExpectedRent > 0 ? ($totalRent / $totalExpectedRent) * 100 : 0;

        // Monthly trends (last 6 months)
        $monthlyTrends = Payment::where('type', 'rent')
            ->where('status', 'completed')
            ->where('payment_date', '>=', Carbon::now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(payment_date, "%Y-%m") as month, SUM(amount) as amount')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Payment methods distribution
        $paymentMethods = Payment::where('type', 'rent')
            ->where('status', 'completed')
            ->selectRaw('payment_method as method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();

        return view('reports.rent', compact(
            'rentPayments', 'totalRent', 'totalTransactions', 
            'pendingRent', 'collectionRate', 'monthlyTrends', 
            'paymentMethods', 'date'
        ));
    }

    public function arrearsReport()
    {
        $tenantsWithArrears = Tenant::where('rent_balance', '>', 0)
            ->with(['property', 'unit'])
            ->get();

        $totalArrears = $tenantsWithArrears->sum('rent_balance');
        $averageArrears = $tenantsWithArrears->count() > 0 ? $totalArrears / $tenantsWithArrears->count() : 0;

        // Arrears by property
        $arrearsByProperty = Tenant::where('rent_balance', '>', 0)
            ->join('properties', 'tenants.property_id', '=', 'properties.id')
            ->selectRaw('properties.name as property_name, SUM(tenants.rent_balance) as total_arrears')
            ->groupBy('properties.id', 'properties.name')
            ->get();

        return view('reports.arrears', compact(
            'tenantsWithArrears', 'totalArrears', 'averageArrears', 'arrearsByProperty'
        ));
    }

 
    public function exportPayments(Request $request)
    {
        $payments = Payment::with(['tenant', 'property'])
            ->when($request->date_range, function($query) use ($request) {
                $dates = explode(' to ', $request->date_range);
                if (count($dates) == 2) {
                    $query->whereBetween('payment_date', [$dates[0], $dates[1]]);
                }
            })
            ->when($request->property_id, function($query, $propertyId) {
                $query->where('property_id', $propertyId);
            })
            ->when($request->type, function($query, $type) {
                $query->where('type', $type);
            })
            ->get();

        // Implement Excel export using Maatwebsite\Excel
        // This would return an Excel file download
        return response()->json(['payments' => $payments]);

        
    }

    public function occupancyReport()
    {
        $user = Auth::user();
        
        // Get properties based on user role
        if ($user->isAdmin()) {
            $properties = Property::withCount(['units', 'tenants'])->get();
        } else {
            $properties = Property::where('user_id', $user->id)
                ->withCount(['units', 'tenants'])
                ->get();
        }

        // Calculate overall statistics
        $totalUnits = Unit::when(!$user->isAdmin(), function($query) use ($user) {
                $query->whereIn('property_id', $user->properties()->pluck('id'));
            })
            ->count();

        $occupiedUnits = Unit::when(!$user->isAdmin(), function($query) use ($user) {
                $query->whereIn('property_id', $user->properties()->pluck('id'));
            })
            ->where('status', 'occupied')
            ->count();

        $vacantUnits = Unit::when(!$user->isAdmin(), function($query) use ($user) {
                $query->whereIn('property_id', $user->properties()->pluck('id'));
            })
            ->where('status', 'vacant')
            ->count();

        $maintenanceUnits = Unit::when(!$user->isAdmin(), function($query) use ($user) {
                $query->whereIn('property_id', $user->properties()->pluck('id'));
            })
            ->where('status', 'maintenance')
            ->count();

        // Calculate occupancy rate
        $occupancyRate = $totalUnits > 0 ? ($occupiedUnits / $totalUnits) * 100 : 0;

        // Occupancy trend (last 6 months)
        $occupancyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');
            
            $occupied = Tenant::when(!$user->isAdmin(), function($query) use ($user) {
                    $query->whereIn('property_id', $user->properties()->pluck('id'));
                })
                ->whereYear('lease_start_date', '<=', $date->year)
                ->whereMonth('lease_start_date', '<=', $date->month)
                ->where(function($query) use ($date) {
                    $query->whereNull('lease_end_date')
                          ->orWhere('lease_end_date', '>=', $date);
                })
                ->where('status', 'active')
                ->count();
            
            $total = Unit::when(!$user->isAdmin(), function($query) use ($user) {
                    $query->whereIn('property_id', $user->properties()->pluck('id'));
                })
                ->count();
            
            $rate = $total > 0 ? ($occupied / $total) * 100 : 0;
            
            $occupancyTrend[] = [
                'month' => $month,
                'occupied' => $occupied,
                'rate' => $rate
            ];
        }

        return view('reports.occupancy', compact(
            'properties',
            'totalUnits',
            'occupiedUnits',
            'vacantUnits',
            'maintenanceUnits',
            'occupancyRate',
            'occupancyTrend'
        ));
    }
}