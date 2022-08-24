<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        "use_stock_opname" => 'boolean'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'store_id', 'id');
    }

    public function roles()
    {
        return $this->hasMany(Role::class, 'store_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'store_users');
    }
}
