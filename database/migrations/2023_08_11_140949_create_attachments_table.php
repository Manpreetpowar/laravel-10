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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('attachment_id',100);
            $table->string('attachment_directory',100);
            $table->string('attachment_filename',250);
            $table->string('attachment_extension',20)->nullable();
            $table->string('attachment_type',20)->nullable();
            $table->string('attachment_size',30)->nullable();
            $table->string('attachment_thumbname',250)->nullable();
            $table->string('attachmentresource_type',50);
            $table->bigInteger('attachmentresource_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
