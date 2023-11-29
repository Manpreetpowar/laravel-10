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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->string('service_order_id');
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('driver_id')->unsigned();
            $table->bigInteger('qc_check_id')->unsigned()->nullable();
            $table->enum('status',['pending','confirmed', 'completed', 'on-hold'])->default('pending');
            $table->enum('service_status',['acc-pending', 'standard-pending', 'hc-pending', 'pvc-pending', 'qc-pending','out-for-delivery','delivered','no-credit','manual-print-required'])->nullable();
            $table->datetime('deliver_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->text('remarks')->nullable();
            $table->bigInteger('total_pieces')->default(0);
            $table->text('acc_remark')->nullable();
            $table->boolean('take_pvc')->default(0);
            $table->string('pvc_dimensions',100)->nullable();

            $table->text('handcraft_remark')->nullable();
            $table->text('thik_remark')->nullable();

            $table->timestamps();


         //FOREIGN KEY CONSTRAINTS
           $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
           $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
           $table->foreign('qc_check_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
