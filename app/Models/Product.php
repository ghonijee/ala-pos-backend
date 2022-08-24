<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        "use_stock_opname" => 'boolean'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
