<?php
// database/seeders/UnitSeeder.php
namespace Database\Seeders;

use App\Models\Property;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run()
    {
        $properties = Property::all();

        foreach ($properties as $property) {
            $totalUnits = $property->total_units;
            $occupiedUnits = $property->occupied_units;

            for ($i = 1; $i <= $totalUnits; $i++) {
                $baseRent = $this->getBaseRent($property->name);
                $rentAmount = $baseRent + ($i * 500);
                
                $status = $i <= $occupiedUnits ? 'occupied' : 'vacant';
                
                // Randomly set some units to maintenance
                if ($status === 'vacant' && rand(1, 10) === 1) {
                    $status = 'maintenance';
                }

                Unit::create([
                    'property_id' => $property->id,
                    'unit_number' => $this->generateUnitNumber($property->name, $i),
                    'rent_amount' => $rentAmount,
                    'deposit_amount' => $rentAmount * 2,
                    'status' => $status,
                    'description' => $this->getUnitDescription($property->name),
                ]);
            }
        }

        $this->command->info('Units seeded successfully!');
    }

    private function getBaseRent($propertyName)
    {
        return match($propertyName) {
            'Garden Apartments' => 15000,
            'City Towers' => 20000,
            'Riverside Villas' => 40000,
            'Metro Apartments' => 18000,
            default => 15000,
        };
    }

    private function generateUnitNumber($propertyName, $index)
    {
        $prefix = match($propertyName) {
            'Garden Apartments' => 'GA',
            'City Towers' => 'CT',
            'Riverside Villas' => 'RV',
            'Metro Apartments' => 'MA',
            default => 'UN',
        };

        return $prefix . str_pad($index, 3, '0', STR_PAD_LEFT);
    }

    private function getUnitDescription($propertyName)
    {
        $descriptions = [
            'Spacious apartment with modern amenities',
            'Comfortable living space with great views',
            'Well-maintained unit with parking space',
            'Luxurious apartment with balcony',
            'Cozy unit perfect for professionals',
            'Modern apartment with security features'
        ];

        return $descriptions[array_rand($descriptions)];
    }
}