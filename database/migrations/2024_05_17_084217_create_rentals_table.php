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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->integer('item_id');
            $table->integer('accessories_id')->nullable();
            $table->date('tgl_inv')->nullable();
            $table->string('total_invoice')->nullable();
            $table->string('no_inv')->nullable();
            $table->string('name_company')->nullable();
            $table->string('addres_company')->nullable();
            $table->bigInteger('phone_company')->nullable();
            $table->string('no_po')->nullable();
            $table->bigInteger('nominal_in');
            $table->bigInteger('nominal_out')->nullable();
            $table->bigInteger('diskon')->nullable();
            $table->bigInteger('ongkir')->nullable();
            $table->string('date_pays')->nullable();
            $table->date('date_start');
            $table->date('date_end');
            $table->longText('image');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
