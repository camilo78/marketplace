<?php

namespace App\Models;

//  Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Filament\Models\Contracts\HasAvatar; // Interfaz para la funcionalidad del avatar
use Filament\Models\Contracts\FilamentUser; // Interfaz para el usuario de Filament
use Spatie\Permission\Traits\HasRoles; // Trait para roles y permisos

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;
    // HasAvatar aquí no es un trait, es una interfaz que se implementa

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo', // Campo para la foto de perfil
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Obtiene la URL del avatar del usuario para Filament.
     *
     * @return string|null
     */
    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->photo) {
            if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
                return $this->photo;
            }
            return asset('storage/' . $this->photo);
        }
        if ($this->email) {
            return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?d=identicon'; // 'identicon' genera un avatar único
        }
        return asset('images/default-avatar.svg');
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true;
    }
}
