<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPemesanansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pemesanans', function (Blueprint $table) {
           $table->id('detail_id'); // INT (Primary Key)
            
            // Kunci Asing (FK)
            // Relasi 1:M dari PEMESANAN
            $table->foreignId('pemesanan_id')->constrained('pemesanans','pemesanan_id')->onDelete('cascade'); 
            // Relasi M:1 ke KURSI
            $table->foreignId('kursi_id')->constrained('kursis','kursi_id')->onDelete('restrict'); 
            
            // Data Keuangan
            $table->decimal('harga_per_kursi', 10, 2);
            
            // Kontrol Penting: Mencegah Kursi Ganda dalam Transaksi
            // Kursi_id dan pemesanan_id harus unik. Ini berarti satu kursi fisik hanya bisa muncul SATU kali di SATU pemesanan.
            $table->unique(['pemesanan_id', 'kursi_id']);
            
            // Kolom Audit
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_pemesanans');
    }
}
