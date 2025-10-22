<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
           $table->id('pembayaran_id'); // INT (Primary Key)
            
            // Kunci Asing (FK) untuk Transaksi Induk (Relasi 1:1)
            $table->foreignId('pemesanan_id')->unique()->constrained('pemesanans','pemesanan_id')->onDelete('cascade'); 
            
            // Kunci Asing (FK) untuk Kasir (Opsional/Audit)
            // Kolom ini nullable karena pemesanan online tidak memiliki Kasir.
            $table->foreignId('user_id')->nullable()->constrained('users','user_id')->onDelete('set null'); 
            
            // Data Keuangan dan Status
            $table->enum('metode_bayar', ['Tunai', 'Debit/Kredit', 'E-Wallet', 'Transfer Bank']);
            $table->decimal('nominal_dibayar', 10, 2);
            $table->dateTime('tanggal_pembayaran');
            $table->enum('status_pembayaran', ['Lunas', 'Gagal', 'Pending'])->default('Pending');
            $table->string('bukti_pembayaran')->nullable();

            
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
        Schema::dropIfExists('pembayarans');
    }
}
