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
        Schema::create('credit_note_items', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('credit_note_id')->unsigned();
			$table->string('item_code',21);
			$table->tinyInteger('quantity')->default(1);
			$table->bigInteger('unit_price')->default(0);
            $table->double('total_price', 8, 2)->default(0);
            $table->timestamps();


         //FOREIGN KEY CONSTRAINTS
           $table->foreign('credit_note_id')->references('id')->on('credit_notes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_note_items');
    }
};
