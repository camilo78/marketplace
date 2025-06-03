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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();

            // --- Relaciones Clave ---
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

            // --- Información Principal del Anuncio ---
            $table->string('title', 255); // Título del anuncio
            $table->string('slug', 255)->unique(); // URL amigable para el SEO
            $table->longText('description'); // Descripción detallada, permite mucho texto
            
            // --- Precio y Moneda ---
            $table->decimal('price', 15, 2)->nullable(); // Precio con 15 dígitos y 2 decimales
            $table->string('currency', 3)->default('HNL'); // Moneda (HNL, USD, etc.)

            // --- Estado y Condición ---
            $table->enum('status', ['active', 'pending', 'sold', 'expired', 'inactive'])->default('pending');
            $table->enum('condition', ['new', 'used', 'refurbished'])->nullable();
            
            // --- Ubicación ---
            $table->string('location_province', 100)->nullable()->index(); // Departamento (Atlántida)
            $table->string('location_city', 100)->nullable()->index(); // Ciudad (La Ceiba)
            $table->string('location_address', 255)->nullable(); // Dirección o barrio

            // --- Atributos Flexibles ---
            // Columna JSON para guardar detalles específicos de la categoría
            // Ej: para carros -> {"mileage": 50000, "year": 2022, "transmission": "automatic"}
            // Ej: para casas -> {"bedrooms": 3, "bathrooms": 2, "area": 120}
            $table->json('attributes')->nullable();

            // --- Control y Métricas ---
            $table->boolean('is_featured')->default(false); // Para destacar anuncios
            $table->unsignedBigInteger('view_count')->default(0); // Contador de vistas
            $table->timestamp('expires_at')->nullable(); // Fecha de expiración del anuncio

            $table->timestamps();
            $table->softDeletes(); // Para "borrado suave", permite recuperar anuncios
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};