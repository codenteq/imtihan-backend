<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Searchable;

    const Admin = 1;

    const Student = 2;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    const MALE = 1;

    const FEMALE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'address',
        'country_id',
        'city_id',
        'state_id',
        'is_active',
        'language_id',
        'avatar',
        'password',
        'role',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'gender' => Gender::class,
    ];

    /*protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->password = Hash::make($user->password);
        });

        static::updating(function ($user) {
            if ($user->password) {
                $user->password = Hash::make($user->password);
            }
        });
    }*/
    // TODO: uncomment this code

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return 'user_index';
    }
}
