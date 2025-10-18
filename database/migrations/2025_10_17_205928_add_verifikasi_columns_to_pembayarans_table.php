<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerifikasiColumnsToPembayaransTable extends Migration
{
    public function up()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Cek kolom mana yang belum ada, baru ditambahkan
            
            // Kolom untuk bukti pembayaran (upload image)
            if (!Schema::hasColumn('pembayarans', 'bukti_pembayaran')) {
                $table->string('bukti_pembayaran')->nullable()->after('status_pembayaran');
            }
            
            // Admin yang verifikasi
            if (!Schema::hasColumn('pembayarans', 'verified_by')) {
                $table->foreignId('verified_by')->nullable()->constrained('users', 'user_id')->onDelete('set null')->after('status_pembayaran');
            }
            
            // Waktu verifikasi - SKIP jika sudah ada
            if (!Schema::hasColumn('pembayarans', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('status_pembayaran');
            }
            
            // Alasan jika ditolak
            if (!Schema::hasColumn('pembayarans', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status_pembayaran');
            }
            
            // Catatan admin (opsional)
            if (!Schema::hasColumn('pembayarans', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('status_pembayaran');
            }
        });
    }

    public function down()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'bukti_pembayaran',
                'verified_by',
                'verified_at',
                'rejection_reason',
                'admin_notes'
            ]);
        });
    }
}