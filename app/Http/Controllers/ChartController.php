<?php
// app/Http/Controllers/ChartController.php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Property;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function monthlyRevenue()
    {
        $revenue = Payment::where('status', 'completed')
            ->whereYear('payment_date', date('Y'))
            ->selectRaw('MONTH(payment_date) as month, SUM(amount) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthRevenue = $revenue->where('month', $i)->first();
            $data[] = $monthRevenue ? $monthRevenue->revenue : 0;
        }

        return response()->json($data);
    }

    public function propertyPerformance()
    {
        $properties = Property::withCount(['tenants', 'units'])->get();
        
        $data = [
            'labels' => $properties->pluck('name'),
            'occupancy_rates' => $properties->pluck('occupancy_rate'),
            'tenant_counts' => $properties->pluck('tenants_count')
        ];

        return response()->json($data);
    }

    public function paymentMethodsDistribution()
    {
        $distribution = Payment::where('status', 'completed')
            ->selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();

        return response()->json($distribution);
    }
}