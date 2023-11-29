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
        Schema::create('account_statement_service_order', function (Blueprint $table) {
			$table->bigInteger('account_statement_id')->unsigned();
            $table->bigInteger('service_order_id')->unsigned();

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('account_statement_id')->references('id')->on('account_statements')->onDelete('cascade');
            $table->foreign('service_order_id')->references('id')->on('service_orders')->onDelete('cascade');

            //SETTING THE PRIMARY KEYS
           $table->primary(['account_statement_id','service_order_id']);
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_statement_service_orders');
    }
};
