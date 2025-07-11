<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens , HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'image',
        'type',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'image',
    ];

    protected $appends =[
        'image_url'
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

    ## Relationships
    public function estates()
    {
        return $this->hasMany(Estate::class);
    }


    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }


    // authourization filament and front
    public function canAccessFilament(): bool
    {
        return in_array($this->type, ['admin','co-admin','seller']);
    }

    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    public function isSeller(): bool
    {
        return $this->type === 'seller';
    }

    public function isBuyer(): bool
    {
        return $this->type === 'buyer';
    }


    //after implements the FilamentUser
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->type, ['admin', 'seller' , 'co-admin']);
    }


    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('front/images/default.png');
        }
        return asset('storage/' . $this->image);
    }
}
