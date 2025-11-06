<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
/**
 * @method static \Illuminate\Database\Eloquent\Builder drivers()
 * @method static \Illuminate\Database\Eloquent\Builder passengers()
 *
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Zikrullo"),
 *     @OA\Property(property="email", type="string", example="zikrullo@gmail.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, example="2025-11-06T10:00:00Z"),
 *     @OA\Property(property="password", type="string", example="$2y$10$hashedpassword"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-11-06T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-11-06T10:00:00Z")
 * )
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const string ROLE_DRIVER = 'driver';
    const string ROLE_PASSENGER = 'passenger';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    public function scopeDrivers($query)
    {
        return $query->where('role', self::ROLE_DRIVER);
    }

    public function scopePassengers($query)
    {
        return $query->where('role', self::ROLE_PASSENGER);
    }
}
