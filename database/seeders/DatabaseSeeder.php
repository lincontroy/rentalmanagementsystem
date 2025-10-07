<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            PropertySeeder::class,
            UnitSeeder::class,
            TenantSeeder::class,
            PaymentSeeder::class,
            ExpenseSeeder::class,
            NoticeSeeder::class,
        ]);
    }
}