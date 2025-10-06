<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
             $table->id('user_id'); // INT (Primary Key) - Menggunakan id() otomatis menjadi Auto-Increment
            // Kolom Identitas dan Login
            $table->enum('role', ['Customer', 'Kasir', 'Admin', 'Owner'])->default('Customer');
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            // Kolom Data Diri
            $table->string('nama_lengkap', 100)->nullable(); // Dibuat nullable sesuai saran opsional
            $table->string('no_telepon', 15)->nullable(); // Dibuat nullable
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
        Schema::dropIfExists('users');
    }
}
