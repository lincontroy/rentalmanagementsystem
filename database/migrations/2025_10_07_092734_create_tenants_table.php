<?php
// database/migrations/2024_01_03_create_tenants_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('id_number')->nullable();
            $table->date('lease_start_date');
            $table->date('lease_end_date')->nullable();
            $table->decimal('rent_balance', 10, 2)->default(0);
            $table->decimal('deposit_balance', 10, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'evicted'])->default('active');
            $table->text('emergency_contact')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tenants');
    }
};