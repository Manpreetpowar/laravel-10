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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('service_order_id')->unsigned();

			$table->enum('payment_terms', ['credit_term','cod','credit_limit'])->default('cod');
            $table->tinyInteger('gst_percent')->default(0);
            $table->double('gst_amount', 8, 2)->default(0);
            $table->tinyInteger('discount_percent')->default(0);
            $table->double('discount_amount', 8, 2)->default(0);
            $table->double('sub_total', 8, 2)->default(0);
            $table->double('amount', 8, 2)->default(0);
            $table->boolean('is_delivered')->default(0);
            $table->boolean('invoice_paid')->default(1);
            $table->date('invoice_paid_date')->nullable()->default(null);


            $table->timestamps();

         //FOREIGN KEY CONSTRAINTS
           $table->foreign('service_order_id')->references('id')->on('service_orders')->onDelete('cascade');
           $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
