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
           $table->id('studio_id'); 
            // Data Utama
            $table->string('nama_studio', 50)->unique(); // Nama ruangan harus unik
            $table->integer('kapasitas'); // Jumlah total kursi
            $table->foreignId('created_by')
                     ->nullable() 
                    ->constrained('users', 'user_id') 
                     ->onDelete('restrict');
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
        Schema::dropIfExists('studios');
    }
}
