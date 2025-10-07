<?php
// database/migrations/2024_01_04_create_payments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique();
            $table->enum('type', ['rent', 'deposit', 'utility', 'other']);
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('month_year'); // Format: YYYY-MM
            $table->enum('payment_method', ['mpesa', 'cash', 'bank', 'cheque'])->default('mpesa');
            $table->enum('status', ['completed', 'pending', 'failed'])->default('completed');
            $table->text('notes')->nullable();
            $table->string('receipt_number')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};