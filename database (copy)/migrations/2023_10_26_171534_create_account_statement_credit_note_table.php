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
        Schema::create('account_statement_credit_note', function (Blueprint $table) {
            $table->bigInteger('account_statement_id')->unsigned(); 
            $table->bigInteger('credit_note_id')->unsigned();
            $table->double('amount', 8, 2)->default(0);

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('account_statement_id')->references('id')->on('account_statements')->onDelete('cascade');
            $table->foreign('credit_note_id')->references('id')->on('credit_notes')->onDelete('cascade');

            //SETTING THE PRIMARY KEYS
            $table->primary(['account_statement_id','credit_note_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_statement_credit_note');
    }
};
