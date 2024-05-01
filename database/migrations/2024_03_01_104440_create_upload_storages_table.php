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
        Schema::create('upload_storages', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->unsignedBigInteger('advertising_id');
            $table->foreign('advertising_id')->references('id')->on('advertisings')->onDelete('cascade');
            $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_storages');
    }
};
