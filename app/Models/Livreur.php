<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livreur extends Model
{
    use HasFactory;

    protected $fillable = [
        'statut_livreur',
    ];

    public function livraisons()
    {
        return $this->hasMany(Livraison::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
