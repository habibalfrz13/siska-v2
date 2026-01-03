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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('judul'); // Atribut judul
            $table->integer('kuota'); // Atribut kuota
            $table->dateTime('pelaksanaan'); // Atribut pelaksanaan
            $table->string('status')->default('tidak aktif'); // Atribut status
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_vendor');
            $table->string('deskripsi');
            $table->string('harga');
            $table->text('foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
