<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use function PHPUnit\Framework\isNull;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

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
     * mutator to encrypt password value
     */
    protected function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'expired_date' => 'datetime',
    ];

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_users');
    }

    public function mainStore()
    {
        return $this->hasOneThrough(Store::class, StoreUser::class, 'user_id', 'id', 'id', 'store_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, RoleUser::class);
    }

    public function role()
    {
        return $this->hasOneThrough(Role::class, RoleUser::class, "user_id", 'id', 'id', 'role_id');
    }

    public function userCrews()
    {
        return $this->belongsToMany(User::class, UserRelation::class, "parent_id", "child_id");
    }

    public function owner()
    {
        return $this->hasOneThrough(User::class, UserRelation::class, "child_id", 'id', 'id', 'parent_id');
    }
}
