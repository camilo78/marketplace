<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use NodeTrait;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active'

    ];

    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->withDepth()->defaultOrder();
}

    public function publications()
    {
        return $this->hasMany(Publication::class);
    }
}
