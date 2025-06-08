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
            $table->string('name', 255); // Nombre de la categoría
            $table->string('slug', 255)->unique(); // URL amigable
            $table->text('description')->nullable();
            // Agregamos las columnas necesarias para Nested Set:
            $table->nestedSet(); // Esto añade: parent_id, _lft y _rgt.
            
            $table->boolean('is_active')->default(true);
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
