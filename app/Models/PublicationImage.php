<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicationImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // --- SOLUCIÓN: Añadir 'is_cover' a los campos rellenables ---
    protected $fillable = [
        'publication_id',
        'file_path',
        'is_cover', // <-- ¡LA PIEZA CLAVE QUE FALTABA!
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // --- BUENA PRÁCTICA: Asegurar que 'is_cover' sea tratado como booleano ---
    protected $casts = [
        'is_cover' => 'boolean',
    ];

    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }
}