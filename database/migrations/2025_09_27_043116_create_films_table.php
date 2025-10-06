<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films', function (Blueprint $table) {
           $table->id('film_id'); // INT (Primary Key)
            // Data Utama dan Inti
            $table->string('judul', 255);
            $table->integer('durasi_menit');
            $table->string('rating', 5); // Batasan usia
             $table->string('genre', 50)->nullable();
            $table->enum('status_tayang', ['Playing Now', 'Upcoming', 'Selesai'])->default('Playing Now');
            // Data Detail Film
            $table->text('sinopsis');
            $table->string('poster_url', 255)->nullable();
            $table->string('trailer_url', 255)->nullable();
            // Data Teknis Produksi
            $table->string('produser', 100)->nullable();
            $table->string('sutradara', 100)->nullable();
            $table->string('penulis', 100)->nullable();
            $table->string('produksi', 100)->nullable();
            $table->text('cast_list')->nullable(); // Menggunakan TEXT untuk daftar pemeran
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
        Schema::dropIfExists('films');
    }
}
