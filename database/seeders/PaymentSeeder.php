<?php
// database/seeders/PaymentSeeder.php
namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Tenant;
use App\Models\Property;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $tenants = Tenant::with('unit')->get();

        foreach ($tenants as $tenant) {
            // Create rent payments for the last 6 months
            for ($i = 0; $i < 6; $i++) {
                $paymentDate = Carbon::now()->subMonths($i);
                
                Payment::create([
                    'tenant_id' => $tenant->id,
                    'property_id' => $tenant->property_id,
                    'transaction_id' => 'TXN' . time() . rand(1000, 9999) . $i,
                    'type' => 'rent',
                    'amount' => $tenant->unit->rent_amount,
                    'payment_date' => $paymentDate,
                    'month_year' => $paymentDate->format('Y-m'),
                    'payment_method' => $this->getRandomPaymentMethod(),
                    'status' => $this->getRandomPaymentStatus($i),
                    'receipt_number' => 'RCPT' . rand(10000, 99999),
                    'notes' => 'Monthly rent payment for ' . $paymentDate->format('F Y'),
                ]);
            }

            // Create deposit payment
            Payment::create([
                'tenant_id' => $tenant->id,
                'property_id' => $tenant->property_id,
                'transaction_id' => 'DPT' . time() . rand(1000, 9999),
                'type' => 'deposit',
                'amount' => $tenant->unit->deposit_amount,
                'payment_date' => $tenant->lease_start_date,
                'month_year' => $tenant->lease_start_date->format('Y-m'),
                'payment_method' => $this->getRandomPaymentMethod(),
                'status' => 'completed',
                'receipt_number' => 'DPT' . rand(10000, 99999),
                'notes' => 'Security deposit payment',
            ]);

            // Random utility payments
            if (rand(1, 3) === 1) {
                Payment::create([
                    'tenant_id' => $tenant->id,
                    'property_id' => $tenant->property_id,
                    'transaction_id' => 'UTL' . time() . rand(1000, 9999),
                    'type' => 'utility',
                    'amount' => rand(500, 3000),
                    'payment_date' => Carbon::now()->subMonths(rand(1, 3)),
                    'month_year' => Carbon::now()->subMonths(rand(1, 3))->format('Y-m'),
                    'payment_method' => $this->getRandomPaymentMethod(),
                    'status' => 'completed',
                    'receipt_number' => 'UTL' . rand(10000, 99999),
                    'notes' => 'Utility bill payment',
                ]);
            }
        }

        $this->command->info('Payments seeded successfully!');
    }

    private function getRandomPaymentMethod()
    {
        $methods = ['mpesa', 'cash', 'bank', 'cheque'];
        return $methods[array_rand($methods)];
    }

    private function getRandomPaymentStatus($monthIndex)
    {
        // Recent payments are more likely to be pending
        if ($monthIndex === 0 && rand(1, 4) === 1) {
            return 'pending';
        }
        
        // Older payments are completed
        return 'completed';
    }
}