<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'nombre_place',
        'disponibilte',
        'personnel_restaurant_id',
    ];

    public function personnelRestaurant()
    {
        return $this->belongsTo(Personnel_Restaurant::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
