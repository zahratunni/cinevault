<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studios', function (Blueprint $table) {
           $table->id('studio_id'); // INT (Primary Key)
            // Data Utama
            $table->string('nama_studio', 50)->unique(); // Nama ruangan harus unik
            $table->integer('kapasitas'); // Jumlah total kursi
            // Kolom Status (Sesuai diskusi sebelumnya)
            // Kode yang benar: Menunjuk ke 'user_id' di tabel 'users'
            $table->foreignId('created_by')
                     ->nullable() // Ditambahkan karena 'created_by' adalah kolom audit (boleh null)
                    ->constrained('users', 'user_id') // <== PENTING: Menentukan PK target
                     ->onDelete('restrict');
            // Kolom Audit
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
        Schema::dropIfExists('studios');
    }
}
