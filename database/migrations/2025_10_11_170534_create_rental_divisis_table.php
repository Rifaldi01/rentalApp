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
        Schema::create('rental_divisis', function (Blueprint $table) {
            $table->id();
            $table->integer('divisi_id');
            $table->integer('item_id');
            $table->string('kode_pinjaman');
            $table->string('description')->nullable();
            $table->integer('status')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_divisis');
    }
};
