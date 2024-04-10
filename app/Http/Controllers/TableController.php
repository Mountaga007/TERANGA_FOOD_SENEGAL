<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    try {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'code_valide' => 401,
                'message' => 'Vous devez être connecté pour voir la liste des tables.'
            ], 401);
        }
        
        // Vérifier si l'utilisateur est administrateur
        if ($user->isAdmin()) {
            // Récupérer la liste des tables
            $tables = Table::all();
            
            return response()->json([
                'code_valide' => 200,
                'message' => 'Liste des tables récupérée avec succès.',
                'tables' => $tables,
            ], 200);
        }
        
        // Vérifier si l'utilisateur a le rôle et le poste requis
        if ($user->role && $user->personnelRestaurant && ($user->role->nom_role == 'personnel restaurant' && $user->personnelRestaurant->poste_occupe == 'Gérant')) {

            // Récupérer la liste des tables
            $tables = Table::all();
            
            return response()->json([
                'code_valide' => 200,
                'message' => 'Liste des tables récupérée avec succès.',
                'tables' => $tables,
            ], 200);
        } else {
            return response()->json([
                'code_valide' => 403,
                'message' => 'Vous n\'avez pas les autorisations nécessaires pour voir la liste des tables.'
            ], 403);
        }
    } catch (\Exception $e) {
        return response()->json([
            'code_valide' => 500,
            'message' => 'Une erreur est survenue lors de la récupération de la liste des tables.',
            'error' => $e->getMessage(),
        ], 500);
    }
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
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'code_valide' => 401,
                'message' => 'Vous devez être connecté pour ajouter une table.'
            ], 401);
        }

        // Vérifier si l'utilisateur a le rôle et le poste requis
        if ($user->role && $user->personnelRestaurant && $user->role->nom_role == 'personnel restaurant' && $user->personnelRestaurant->poste_occupe == 'Gérant') {
            
            $table = new Table([
                'numero' => $request->numero,
                'nombre_place' => $request->nombre_place,
                'disponibilte' => 'disponible',
                'personnel_restaurant_id' => $user->personnelRestaurant->id,
            ]);
            $table->save();
            
            return response()->json([
                'code_valide' => 201,
                'message' => 'La table a été ajoutée avec succès.',
            ], 201);
        } else {
            return response()->json([
                'code_valide' => 403,
                'message' => 'Vous n\'avez pas les autorisations nécessaires pour ajouter une table en tant que personnel de restaurant.'
            ], 403);
        }
    } catch (\Exception $e) {
        return response()->json([
            'code_valide' => 500,
            'message' => 'Une erreur est survenue lors de l\'ajout de la table.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'code_valide' => 401,
                    'message' => 'Vous devez être connecté pour voir les détails d\'une table.'
                ], 401);
            }
            
            // Vérifier si l'utilisateur a le rôle et le poste requis ou s'il est administrateur
            if ($user->role && $user->personnelRestaurant && $user->role->nom_role == 'personnel restaurant' && $user->personnelRestaurant->poste_occupe == 'Gérant' || $user->isAdmin()) {
                // Récupérer la table par son identifiant
                $table = Table::findOrFail($id);
                
                return response()->json([
                    'code_valide' => 200,
                    'message' => 'Détails de la table récupérés avec succès.',
                    'table' => $table,
                ], 200);
            } else {
                return response()->json([
                    'code_valide' => 403,
                    'message' => 'Vous n\'avez pas les autorisations nécessaires pour voir les détails d\'une table.'
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'code_valide' => 500,
                'message' => 'Une erreur est survenue lors de la récupération des détails de la table.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Table $table)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     try {
    //         $user = Auth::user();
            
    //         if (!$user) {
    //             return response()->json([
    //                 'code_valide' => 401,
    //                 'message' => 'Vous devez être connecté pour mettre à jour les détails d\'une table.'
    //             ], 401);
    //         }
            
    //         // Vérifier si l'utilisateur a le rôle et le poste requis ou s'il est administrateur
    //         if ($user->role && $user->personnelRestaurant && $user->role->nom_role == 'personnel restaurant' && $user->personnelRestaurant->poste_occupe == 'Gérant' || $user->isAdmin()) {
    //             // Récupérer la table par son identifiant
    //             $table = Table::findOrFail($id);
                
    //             // Mettre à jour les attributs de la table avec les données de la requête
    //             $table->update([
    //                 'numero' => $request->numero,
    //                 'nombre_place' => $request->nombre_place,
    //                 'disponibilte' => $request->disponibilte,
    //             ]);
                
    //             return response()->json([
    //                 'code_valide' => 200,
    //                 'message' => 'Détails de la table mis à jour avec succès.',
    //                 'table' => $table,
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'code_valide' => 403,
    //                 'message' => 'Vous n\'avez pas les autorisations nécessaires pour mettre à jour les détails d\'une table.'
    //             ], 403);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'code_valide' => 500,
    //             'message' => 'Une erreur est survenue lors de la mise à jour des détails de la table.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
    
    public function update(Request $request, $id)
    {
        try {
            // Trouver la table par son identifiant
            $table = Table::findOrFail($id);
    
            // Vérifier si le numéro de table à mettre à jour existe déjà dans la base de données
            if ($request->numero != $table->numero && Table::where('numero', $request->numero)->exists()) {
                return response()->json([
                    'code_valide' => 400,
                    'message' => 'Ce numéro de table existe déjà dans la base de données, on ne peut pas changer le numéro d\'une table à partir de l\'ID d\'une autre table.'
                ], 400);
            }
    
            // Mettre à jour les attributs de la table avec les données de la requête
            $table->update([
                'numero' => $request->numero,
                'nombre_place' => $request->nombre_place,
                'disponibilte' => $request->disponibilte,
                // Autres champs à mettre à jour
            ]);
    
            return response()->json([
                'code_valide' => 200,
                'message' => 'Table mise à jour avec succès.',
                'table' => $table,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code_valide' => 500,
                'message' => 'Une erreur est survenue lors de la mise à jour de la table.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    try {
        // Trouver la table par son identifiant
        $table = Table::findOrFail($id);

        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Vérifier si l'utilisateur est autorisé à supprimer la table
        if ($user->isAdmin() || ($user->personnelRestaurant && $user->personnelRestaurant->poste_occupe === 'Gérant' && $table->personnel_restaurant_id === $user->personnelRestaurant->id)) {
            // Supprimer la table de la base de données
            $table->delete();

            return response()->json([
                'code_valide' => 200,
                'message' => 'Table supprimée avec succès.',
            ], 200);
        } else {
            return response()->json([
                'code_valide' => 403,
                'message' => 'Vous n\'avez pas les autorisations nécessaires pour supprimer cette table.'
            ], 403);
        }
    } catch (\Exception $e) {
        return response()->json([
            'code_valide' => 500,
            'message' => 'Une erreur est survenue lors de la suppression de la table.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
}
