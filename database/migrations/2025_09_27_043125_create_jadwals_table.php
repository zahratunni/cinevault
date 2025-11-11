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
        $table->foreignId('film_id')->constrained('films', 'film_id')->onDelete('cascade');
        $table->foreignId('studio_id')->constrained('studios', 'studio_id')->onDelete('cascade');
        $table->foreignId('created_by')->constrained('users', 'user_id')->onDelete('restrict');
        $table->date('tanggal_tayang');
        $table->time('jam_mulai');
        $table->time('jam_selesai');
        $table->decimal('harga_reguler', 10, 2);
        $table->enum('status_jadwal', ['Active', 'Canceled', 'Full'])->default('Active');
        $table->unique(['studio_id', 'tanggal_tayang', 'jam_mulai']);
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
