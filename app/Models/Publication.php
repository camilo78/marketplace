<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publication extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que pueden asignarse masivamente.
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'currency',
        'status',
        'condition',
        'location_province',
        'location_city',
        'location_address',
        'attributes',
        'is_featured',
        'view_count',
        'expires_at',
    ];

    /**
     * Los atributos que deben castearse a tipos específicos.
     */
    protected $casts = [
        'attributes' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que creó la publicación.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con la categoría de la publicación.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}