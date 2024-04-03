<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Utilisteur extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            // Créer un nouvel utilisateur avec le rôle "utilisateur"
            $user = new User();
            $user->prenom = $request->prenom;
            $user->nom = $request->nom;
            $user->telephone = $request->telephone;
            $user->adresse = $request->adresse;
            $user->email = $request->email;
            $user->password = Hash::make($request->password); // Hacher le mot de passe
            $user->statut_compte = true; // Activer le compte de l'utilisateur
    
            // Gérer l'upload de l'image
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $user->image = $imagePath;
            }
    
            // Trouver le rôle "utilisateur"
            $userRole = Role::where('nom_role', 'utilisateur')->first();
    
            if (!$userRole) {
                // Si le rôle "utilisateur" n'existe pas, le créer
                $userRole = new Role();
                $userRole->nom_role = 'utilisateur';
                $userRole->save();
            }
    
            // Associer le rôle "utilisateur" à l'utilisateur
            $user->role()->associate($userRole);
    
            // Sauvegarder l'utilisateur
            $user->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Inscription réussie. Vous êtes désormais enregistré en tant qu\'utilisateur.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'inscription.', 
                'error' => $e->getMessage()
            ], 500);
        }

    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
