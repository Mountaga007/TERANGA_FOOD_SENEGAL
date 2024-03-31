<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel_Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'poste_occupe',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function tables()
    {
        return $this->hasMany(Table::class);
    }
}
