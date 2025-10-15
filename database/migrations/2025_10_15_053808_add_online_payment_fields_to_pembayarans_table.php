<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnlinePaymentFieldsToPembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Jenis pembayaran (Online/Offline)
            $table->enum('jenis_pembayaran', ['Online', 'Offline'])->default('Offline')->after('status_pembayaran');
            
            // Metode pembayaran online (DANA, OVO, dll)
            $table->string('metode_online', 50)->nullable()->after('jenis_pembayaran');
            
            // Status verifikasi untuk pembayaran online
            $table->enum('status_verifikasi', ['pending', 'approved', 'rejected'])->nullable()->after('metode_online');
            
            // Admin yang verifikasi
            $table->foreignId('verified_by')->nullable()->constrained('users', 'user_id')->onDelete('set null')->after('status_verifikasi');
            
            // Waktu verifikasi
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            
            // Catatan admin (jika reject)
            $table->text('catatan_verifikasi')->nullable()->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'jenis_pembayaran',
                'metode_online',
                'status_verifikasi',
                'verified_by',
                'verified_at',
                'catatan_verifikasi'
            ]);
        });
    }
}