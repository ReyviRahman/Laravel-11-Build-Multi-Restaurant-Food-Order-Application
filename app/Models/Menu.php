<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = [];

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }
}
