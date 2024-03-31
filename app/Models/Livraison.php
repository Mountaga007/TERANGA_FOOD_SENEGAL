<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livraison extends Model
{
    use HasFactory;

    protected $fillable = [
        'statut_livraison',
        'adresse_livraison',
    ];

    public function livreur()
    {
        return $this->belongsTo(Livreur::class);
    }

    public function commande()
    {
        return $this->hasOne(Commande::class);
    }
}
