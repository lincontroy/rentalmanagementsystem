<?php
// database/seeders/PropertySeeder.php
namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('email', 'admin@property.com')->first();
        $manager = User::where('email', 'admin@gmail.com')->first();
        $regional = User::where('email', 'regional@property.com')->first();

        // Property 1 - Garden Apartments (19 tenants)
        $property1 = Property::create([
            'user_id' => $manager->id,
            'name' => 'Garden Apartments',
            'paybill_number' => '123456',
            'address' => '123 Main Street, Westlands, Nairobi',
            'total_units' => 25,
            'occupied_units' => 19,
            'monthly_rent_total' => 425000,
            'is_active' => true,
        ]);

        // Property 2 - City Towers (48 tenants)
        $property2 = Property::create([
            'user_id' => $manager->id,
            'name' => 'City Towers',
            'paybill_number' => '654321',
            'address' => '456 Business Avenue, Upper Hill, Nairobi',
            'total_units' => 60,
            'occupied_units' => 48,
            'monthly_rent_total' => 1200000,
            'is_active' => true,
        ]);

       

       

        $this->command->info('Properties seeded successfully!');
    }
}