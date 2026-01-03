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
        Schema::create('course_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('course_modules')->onDelete('cascade');
            $table->string('title');
            $table->enum('type', ['video', 'text', 'file', 'link'])->default('text');
            $table->longText('content')->nullable();     // untuk type=text (HTML content)
            $table->string('file_path')->nullable();      // untuk type=file
            $table->string('video_url')->nullable();      // untuk type=video (YouTube/embed)
            $table->integer('duration')->nullable();      // durasi dalam menit
            $table->integer('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_materials');
    }
};
