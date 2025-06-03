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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); // Nombre de la categoría (Ej: "Bienes Raíces")
            $table->string('slug', 255)->unique(); // URL amigable (Ej: "bienes-raices")
            $table->text('description')->nullable(); // Una descripción opcional

            // La magia para las categorías anidadas:
            // Esta columna guarda el ID de la categoría padre.
            // Si es null, significa que es una categoría principal.
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories') // Se relaciona con la misma tabla 'categories'
                ->onUpdate('cascade')
                ->onDelete('set null'); // Si se borra un padre, los hijos quedan como principales

            $table->boolean('is_active')->default(true); // Para activar/desactivar categorías
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
