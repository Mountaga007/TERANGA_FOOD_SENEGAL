<?php

namespace App\Http\Controllers;

use App\Models\Personnel_Restaurant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class PersonnelRestaurantController extends Controller
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
        // Vérifier si l'utilisateur actuel a le rôle d'administrateur
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Vous n\'avez pas les autorisations nécessaires pour effectuer cette action.'
            ], 403);
        }

        // Trouver le rôle "personnel restaurant"
        $personnelRestoRole = Role::where('nom_role', 'personnel restaurant')->first();

        // Si le rôle "personnel restaurant" n'existe pas, le créer
        if (!$personnelRestoRole) {
            $personnelRestoRole = new Role();
            $personnelRestoRole->nom_role = 'personnel restaurant';
            $personnelRestoRole->save();
        }

        // Créer un nouvel utilisateur
        $user = new User();
        $user->prenom = $request->prenom;
        $user->nom = $request->nom;
        $user->telephone = $request->telephone;
        $user->adresse = $request->adresse;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->statut_compte = true; // Activer le compte du personnel restaurant
        $user->role_id = $personnelRestoRole->id; // Associer le rôle "personnel restaurant" à l'utilisateur
        $user->save();
        $password = $request->password;

        // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $user->image = $imagePath;
            $user->save();
        }

        // Création du personnel restaurant
        $personnelResto = $user->personnelRestaurant()->create([
            'poste_occupe' => $request->poste_occupe,
        ]);

        if ($personnelResto) {
            // Envoi de l'email de confirmation
            Mail::send('emailInscriptionPersonnelRestaurant', [
                'nom' => $user->nom,
                'email' => $user->email,
                'password' => $password
            ], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Notification d\'inscription réussie');
            });

            return response()->json([
                'success' => true,
                'message' => 'Inscription réussie. Vous êtes désormais enregistré en tant que gérant du restaurant.'
            ], 201);
        } else {
            // En cas d'échec, supprimer l'utilisateur précédemment créé
            $user->delete();
            return response()->json([
                "code_valide" => 500,
                "message" => "Une erreur s'est produite lors de l'inscription."
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
    public function show(Personnel_Restaurant $personnel_Restaurant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Personnel_Restaurant $personnel_Restaurant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Personnel_Restaurant $personnel_Restaurant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Personnel_Restaurant $personnel_Restaurant)
    {
        //
    }
}


