<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->timestamp('tiket_dicetak_at')->nullable()->after('tanggal_pemesanan');
            $table->unsignedBigInteger('dicetak_oleh')->nullable()->after('tiket_dicetak_at');
        });
    }

    public function down()
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->dropColumn(['tiket_dicetak_at', 'dicetak_oleh']);
        });
    }
};