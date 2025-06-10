<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publication_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publication_id')->constrained('publications')->onDelete('cascade');
            $table->string('file_path'); // Ruta o nombre del archivo de la imagen
            $table->boolean('is_cover')->default(false); // Si es la imagen principal/portada
            $table->unsignedInteger('order')->default(0); // Para ordenar las imágenes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publication_images');
    }
};