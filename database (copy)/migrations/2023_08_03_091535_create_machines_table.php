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
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
			$table->string('machine_id',21);
            $table->bigInteger('operator_id')->unsigned()->nullable();
			$table->string('machine_name',100)->nullable();
			$table->string('brand_name',50)->nullable();
			$table->string('model',50)->nullable();
			$table->bigInteger('total_mileage')->unsigned()->default(0);
			$table->bigInteger('current_mileage')->unsigned()->default(0);
			$table->bigInteger('mileage_servicing_reminder')->unsigned()->default(10000);
            $table->timestamps();

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('operator_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
