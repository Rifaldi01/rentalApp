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
        Schema::create('debt_servics', function (Blueprint $table) {
            $table->id();
            $table->integer('service_id');
            $table->integer('bank_id')->nullable();
            $table->string('pay_debts');
            $table->string('penerima')->nullable();
            $table->date('date_pay');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debt_servics');
    }
};
