<?php

namespace App\Http\Controllers;

use App\Models\Livreur;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LivreurController extends Controller
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
            // Trouver le rôle "livreur"
            $livreurRole = Role::where('nom_role', 'livreur')->first();
    
            if (!$livreurRole) {
                // Si le rôle "livreur" n'existe pas, créer-le
                $livreurRole = new Role();
                $livreurRole->nom_role = 'livreur';
                $livreurRole->save();
            }
    
            // Créer un nouvel utilisateur
            $user = new User();
            $user->prenom = $request->prenom;
            $user->nom = $request->nom;
            $user->telephone = $request->telephone;
            $user->adresse = $request->adresse;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->statut_compte = true; // Activer le compte du livreur
            $user->role_id = $livreurRole->id; // Associer le rôle "livreur" à l'utilisateur
            $user->save();
    
            // Gérer l'upload de l'image
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $user->image = $imagePath;
                $user->save();
            }
    
            // Création du Livreur
            $livreur = $user->livreur()->create([
                'statut_livreur' => "disponible",
            ]);
    
            if ($livreur) {
            return response()->json([
                'success' => true,
                'message' => 'Inscription réussie. Bravo ! Vous êtes désormais enregistré en tant que livreur.'
            ], 201);
        } else {
            // En cas d'échec, supprimer l'utilisateur précédemment créé
            $user->delete();

            return response()->json([
                "code_valide" => 500,
                "message" => "Une erreur s'est produite lors de l'inscription.",
            ], 500);
        }
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
    public function show(Livreur $livreur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Livreur $livreur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Livreur $livreur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livreur $livreur)
    {
        //
    }
}
