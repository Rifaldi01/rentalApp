<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->unsignedInteger('installment_no')->nullable()->after('rental_id');
        });

        // Backfill nomor cicilan untuk seluruh pembayaran lama per rental.
        DB::table('debts')->orderBy('rental_id')->orderBy('date_pay')->orderBy('id')
            ->get()->groupBy('rental_id')->each(function ($payments) {
                foreach ($payments->values() as $index => $payment) {
                    DB::table('debts')->where('id', $payment->id)
                        ->update(['installment_no' => $index + 1]);
                }
            });

        Schema::table('debts', function (Blueprint $table) {
            $table->unique(['rental_id', 'installment_no'], 'debts_rental_installment_unique');
        });
    }

    public function down(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->dropUnique('debts_rental_installment_unique');
            $table->dropColumn('installment_no');
        });
    }
};
