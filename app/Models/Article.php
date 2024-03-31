<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_article',
        'description_article',
        'prix_article',
        'image_article',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function personnelRestaurant()
    {
        return $this->belongsTo(Personnel_Restaurant::class);
    }
}
