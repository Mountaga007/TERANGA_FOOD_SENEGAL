<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_article_commander',
        'statut_commande',
        'montant_total_commande',
    ];

    public function livraison()
    {
        return $this->belongsTo(Livraison::class);
    }

    public function avisCommandes()
    {
        return $this->hasMany(Avis_Commande::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
