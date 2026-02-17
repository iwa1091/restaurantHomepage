<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'table_id')) {
                $table->foreignId('table_id')->nullable()->after('service_id')->constrained('tables')->nullOnDelete();
            }
            if (!Schema::hasColumn('reservations', 'party_size')) {
                $table->integer('party_size')->default(1)->after('table_id');
            }
            if (!Schema::hasColumn('reservations', 'seat_preference')) {
                $table->string('seat_preference')->nullable()->after('party_size');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'seat_preference')) {
                $table->dropColumn('seat_preference');
            }
            if (Schema::hasColumn('reservations', 'party_size')) {
                $table->dropColumn('party_size');
            }
            if (Schema::hasColumn('reservations', 'table_id')) {
                $table->dropConstrainedForeignId('table_id');
            }
        });
    }
};
