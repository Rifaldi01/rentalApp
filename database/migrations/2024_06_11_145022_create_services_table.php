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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('phone')->nullable();
            $table->string('item');
            $table->string('no_seri');
            $table->string('descript');
            $table->string('type');
            $table->bigInteger('nominal_in');
            $table->bigInteger('nominal_out')->nullable();
            $table->bigInteger('diskon')->nullable();
            $table->bigInteger('biaya_ganti')->nullable();
            $table->bigInteger('ongkir')->nullable();
            $table->date('date_service');
            $table->date('date_finis')->nullable();
            $table->string('jenis_service');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
