<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKursisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kursis', function (Blueprint $table) {
             $table->id('kursi_id'); // INT (Primary Key)
            
            // Kunci Asing (FK)
            $table->foreignId('studio_id')->constrained('studios', 'studio_id')->onDelete('cascade'); // FK ke STUDIO

            // Data Utama Kursi
            $table->string('kode_kursi', 10);
            $table->string('baris', 5);
            $table->integer('nomor_kursi');
            
            // Kontrol Penting: Mencegah Kursi Ganda di Studio yang Sama
            // Kode unik kursi hanya berlaku dalam satu studio.
            $table->unique(['studio_id', 'kode_kursi']);
            
            // Kolom Audit (Opsional, tapi baik untuk pencatatan)
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
        Schema::dropIfExists('kursis');
    }
}
