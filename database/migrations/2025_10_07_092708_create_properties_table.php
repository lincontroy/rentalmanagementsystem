<?php
// database/migrations/2024_01_01_create_properties_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Add this line
            $table->string('name');
            $table->string('paybill_number')->unique();
            $table->text('address')->nullable();
            $table->integer('total_units')->default(0);
            $table->integer('occupied_units')->default(0);
            $table->decimal('monthly_rent_total', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
};