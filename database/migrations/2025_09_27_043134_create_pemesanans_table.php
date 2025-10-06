<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemesanansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id('pemesanan_id'); // INT (Primary Key)
            // Kunci Asing (FK)
            $table->foreignId('user_id')->constrained('users','user_id')->onDelete('restrict'); // FK ke USER (Customer/Kasir)
            $table->foreignId('jadwal_id')->constrained('jadwals','jadwal_id')->onDelete('restrict'); // FK ke JADWAL
            // Data Identifikasi & Status
            $table->string('kode_transaksi', 50)->unique(); // Kode unik QR/Invoice
            $table->enum('jenis_pemesanan', ['Online', 'Offline']);
            $table->enum('status_pemesanan', ['Lunas', 'Menunggu Bayar', 'Kadaluarsa', 'Dibatalkan'])->default('Menunggu Bayar');
            // Data Keuangan
            $table->decimal('harga_dasar_total', 10, 2);
            $table->decimal('total_bayar', 10, 2);
            // Kolom Audit
            $table->dateTime('tanggal_pemesanan');
            $table->timestamps(); // Menciptakan created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemesanans');
    }
}
