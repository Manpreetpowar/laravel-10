<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
			$table->string('client_id',21)->unique();
			$table->string('client_name', 100)->nullable();
			$table->string('client_email', 100)->nullable();
			$table->boolean('auto_send_email')->default(1);
			$table->text('client_address')->nullable();
			$table->string('poc_name', 100)->nullable();
			$table->string('poc_contact', 100)->nullable();
			$table->enum('payment_terms', ['credit_term','cod','credit_limit'])->default('cod');
			$table->double('credit_limit', 8, 2)->default(0);
            $table->double('credit_notes', 8, 2)->default(0);
			$table->double('outstanding', 8, 2)->default(0);
			$table->boolean('apply_discount')->default(0);
			$table->double('discount', 8, 2)->default(0);
			$table->double('lifetime_revenue', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
