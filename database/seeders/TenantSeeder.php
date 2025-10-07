<?php
// database/seeders/TenantSeeder.php
namespace Database\Seeders;

use App\Models\Property;
use App\Models\Unit;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TenantSeeder extends Seeder
{
    public function run()
    {
        $properties = Property::all();

        foreach ($properties as $property) {
            $occupiedUnits = Unit::where('property_id', $property->id)
                                ->where('status', 'occupied')
                                ->get();

            foreach ($occupiedUnits as $unit) {
                $tenantNumber = $this->getTenantNumber($unit);
                
                Tenant::create([
                    'property_id' => $property->id,
                    'unit_id' => $unit->id,
                    'name' => $this->generateTenantName($tenantNumber, $property->name),
                    'email' => $this->generateTenantEmail($tenantNumber, $property->name),
                    'phone' => $this->generatePhoneNumber(),
                    'id_number' => $this->generateIdNumber(),
                    'lease_start_date' => $this->generateLeaseStartDate(),
                    'lease_end_date' => $this->generateLeaseEndDate(),
                    'rent_balance' => rand(0, 1) ? 0 : $unit->rent_amount,
                    'deposit_balance' => $unit->deposit_amount,
                    'status' => 'active',
                    'emergency_contact' => $this->generateEmergencyContact(),
                ]);
            }
        }

        $this->command->info('Tenants seeded successfully!');
    }

    private function getTenantNumber($unit)
    {
        return (int) filter_var($unit->unit_number, FILTER_SANITIZE_NUMBER_INT);
    }

    private function generateTenantName($number, $propertyName)
    {
        $firstNames = ['John', 'Mary', 'David', 'Sarah', 'Michael', 'Grace', 'James', 'Lucy', 'Robert', 'Ann'];
        $lastNames = ['Mwangi', 'Kariuki', 'Omondi', 'Nyong\'o', 'Kamau', 'Atieno', 'Odhiambo', 'Achieng', 'Kipchoge', 'Wambui'];
        
        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        
        return "{$firstName} {$lastName} - {$propertyName}";
    }

    private function generateTenantEmail($number, $propertyName)
    {
        $propertySlug = strtolower(str_replace(' ', '', $propertyName));
        return "tenant{$number}@{$propertySlug}.com";
    }

    private function generatePhoneNumber()
    {
        return '07' . rand(10, 99) . rand(100000, 999999);
    }

    private function generateIdNumber()
    {
        return rand(10000000, 39999999);
    }

    private function generateLeaseStartDate()
    {
        return Carbon::now()->subMonths(rand(1, 24));
    }

    private function generateLeaseEndDate()
    {
        return Carbon::now()->addMonths(rand(6, 24));
    }

    private function generateEmergencyContact()
    {
        $names = ['Spouse', 'Parent', 'Sibling', 'Friend', 'Relative'];
        $relation = $names[array_rand($names)];
        $phone = '07' . rand(10, 99) . rand(100000, 999999);
        
        return "{$relation}: {$phone}";
    }
}