<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwals', function (Blueprint $table) {
         $table->bigIncrements('jadwal_id'); 

    // Kunci Asing (FK)
    $table->foreignId('film_id')->constrained('films', 'film_id')->onDelete('cascade'); // FK ke FILM
    $table->foreignId('studio_id')->constrained('studios', 'studio_id')->onDelete('cascade'); // FK ke STUDIO
    $table->foreignId('created_by')->constrained('users', 'user_id')->onDelete('restrict'); // FK ke USER (Admin)
    
    // Data Waktu dan Harga
    $table->date('tanggal_tayang');
    $table->time('jam_mulai');
    $table->time('jam_selesai');
    $table->decimal('harga_reguler', 10, 2);
    
    // Status dan Kontrol
    $table->enum('status_jadwal', ['Active', 'Canceled', 'Full'])->default('Active');
    
    // Kontrol Penting: Mencegah Bentrok Jadwal
    $table->unique(['studio_id', 'tanggal_tayang', 'jam_mulai']);
    
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
        Schema::dropIfExists('jadwals');
    }
}
