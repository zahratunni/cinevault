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
        // Hapus kolom-kolom yang mungkin sudah ada (duplikat)
        Schema::table('pembayarans', function (Blueprint $table) {
            $columns = ['snap_token', 'transaction_id', 'payment_type', 'metode_bayar', 
                       'jenis_pembayaran', 'metode_online', 'status_verifikasi', 'catatan_verifikasi'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('pembayarans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // Tambahkan kolom-kolom baru
        Schema::table('pembayarans', function (Blueprint $table) {
            // Kolom midtrans - tambah setelah status_pembayaran
            $table->string('snap_token')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('metode_bayar')->nullable();
            $table->enum('jenis_pembayaran', ['Online', 'Offline'])->nullable()->default('Online');
            $table->string('metode_online')->nullable();
            $table->enum('status_verifikasi', ['pending', 'approved', 'rejected'])->nullable()->default('pending');
            $table->text('catatan_verifikasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn([
                'snap_token',
                'transaction_id', 
                'payment_type',
                'metode_bayar',
                'jenis_pembayaran',
                'metode_online',
                'status_verifikasi',
                'catatan_verifikasi'
            ]);
        });
    }
};