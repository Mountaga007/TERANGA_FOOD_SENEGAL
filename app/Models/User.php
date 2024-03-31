<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Controllers\PersonnelRestaurantController;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prenom',
        'nom',
        'telephone',
        'adresse',
        'image',
        'email',
        'password',
        'statut_compte',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];/**
    * Détermine si l'utilisateur est un administrateur.
    *
    * @return bool
    */
   public function isAdmin()
   {
       // Vérifie si l'utilisateur a le rôle d'administrateur
       return $this->role && $this->role->nom_role === 'admin';
   }


    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function livreur()
    {
        return $this->hasOne(Livreur::class);
    }

    public function personnelRestaurant()
    {
        return $this->hasOne(Personnel_Restaurant::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function avisCommandes()
    {
        return $this->hasMany(Avis_Commande::class);
    }
}
