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
        Schema::create('account_statements', function (Blueprint $table) {
            $table->id();
            $table->string('account_statement_id');
            $table->bigInteger('client_id')->unsigned();
            $table->double('due_amount', 8, 2)->default(0);
            $table->double('credit_amount', 8, 2)->default(0);
            $table->double('payable_amount', 8, 2)->default(0);
            $table->enum('status',['unpaid', 'paid', 'reject'])->default('unpaid');
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
        Schema::dropIfExists('account_statements');
    }
};
