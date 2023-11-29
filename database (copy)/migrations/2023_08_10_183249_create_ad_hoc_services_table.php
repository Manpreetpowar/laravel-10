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
        Schema::create('ad_hoc_services', function (Blueprint $table) {
            $table->id();
			$table->string('service_id',21);
			$table->bigInteger('machine_id')->unsigned();
			$table->date('reminder_date')->nullable();
			$table->date('service_date')->nullable();
			$table->text('remark')->nullable();
			$table->text('document')->nullable();
            $table->timestamps();

         //FOREIGN KEY CONSTRAINTS
           $table->foreign('machine_id')->references('id')->on('machines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_hoc_services');
    }
};
