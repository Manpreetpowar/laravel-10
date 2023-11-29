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
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();
			$table->string('note_id',21)->unique();
			$table->bigInteger('client_id')->unsigned();
			$table->boolean('apply_gst')->default(0);
			$table->string('terms',100)->nullable();
			$table->double('amount', 8, 2)->default(0);
			$table->enum('status', ['redeemed', 'unredeemed', 'partial'])->default('unredeemed');
            $table->double('partial_amount', 8, 2)->default(0);
            $table->double('gst_percent',8,2)->default(0);
			$table->string('remark')->nullable();
            $table->timestamps();


         //FOREIGN KEY CONSTRAINTS
           $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_notes');
    }
};
