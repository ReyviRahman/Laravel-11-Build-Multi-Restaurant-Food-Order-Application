<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Relasi ke Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // Relasi ke Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
