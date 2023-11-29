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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('service_order_id')->unsigned();
            $table->bigInteger('product_variant_id')->unsigned()->nullable();
            $table->bigInteger('operator_id')->unsigned()->nullable();
            $table->bigInteger('machine_id')->unsigned()->nullable();
            $table->string('item_name')->nullable();
            $table->bigInteger('quantity')->default(0);
            $table->text('remarks')->nullable();
            $table->double('total_run', 8, 2)->default(0);
            $table->double('price', 8, 2)->default(0);
            $table->double('amount', 8, 2)->default(0);
            $table->enum('type',['custom','inventory'])->default('inventory');

            $table->timestamps();

         //FOREIGN KEY CONSTRAINTS
           $table->foreign('service_order_id')->references('id')->on('service_orders')->onDelete('cascade');
           $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
           $table->foreign('operator_id')->references('id')->on('users');
           $table->foreign('machine_id')->references('id')->on('machines');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
