<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Affiche la liste des rôles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            // Vérifier si l'utilisateur actuel est un administrateur
            if (!$request->user()->isAdmin()) {
                return response()->json([
                    'message' => 'Vous n\'avez pas les autorisations nécessaires pour afficher la liste des rôles.'
                ], 403);
            }

            // Récupérer la liste des rôles
            $roles = Role::all();

            return response()->json([
                'code_valide' => 200,
                'message' => 'La liste des roles a été bien récupérée.',
                'liste_des_roles' => $roles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code_valide' => 500,
                'message' => 'Une erreur est survenue lors de la récupération de la liste des rôles.',
                'error' => $e->getMessage()
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
        // Vérifier si l'utilisateur actuel a le rôle d'administrateur
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Vous n\'avez pas les autorisations nécessaires pour effectuer cette action.'
            ], 403);
        }

        // Vérifier si le rôle existe déjà
        $existingRole = Role::where('nom_role', $request->nom_role)->first();
        if ($existingRole) {
            return response()->json([
                'message' => 'Ce rôle existe déjà.'
            ], 400);
        }

        // Créer un nouveau rôle
        $role = new Role();
        $role->nom_role = $request->nom_role;
        $role->save();

        return response()->json([
            'code_valide' => 201,
            'message' => 'Le rôle a été ajouté avec succès.'
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'code_valide' => 500,
            'message' => 'Une erreur est survenue lors de l\'ajout du rôle.', 'error' => $e->getMessage()
        ], 500);
    }
}   

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {
            // Vérifier si l'utilisateur actuel est un administrateur
            if (!$request->user()->isAdmin()) {
                return response()->json([
                    'message' => 'Vous n\'avez pas les autorisations nécessaires pour afficher un rôle spécifique.'
                ], 403);
            }

            // Récupérer le rôle spécifique
            $role = Role::findOrFail($id);

            return response()->json([
                'code_valide' => 200,
                'message' => 'Le rôle a été trouvé avec succès.',
                'le_role_specifique' => $role,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code_valide' => 500,
                'message' => 'Une erreur est survenue lors de la récupération du rôle spécifique.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Met à jour le rôle spécifié.
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Vérifier si l'utilisateur actuel a le rôle d'administrateur
            if (!$request->user()->isAdmin()) {
                return response()->json([
                    'message' => 'Vous n\'avez pas les autorisations nécessaires pour effectuer cette action.'
                ], 403);
            }

            // Trouver le rôle à mettre à jour
            $role = Role::find($id);
            if (!$role) {
                return response()->json([
                    'code_valide' => 404,
                    'message' => 'Le rôle spécifié n\'existe pas.'
                ], 404);
            }

            // Mettre à jour les détails du rôle
            $role->nom_role = $request->input('nom_role');
            $role->save();

            return response()->json([
                'code_valide' => 200,
                'message' => 'Le rôle a été mis à jour avec succès.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour du rôle.', 'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Trouver le rôle à supprimer
            $role = Role::find($id);
            if (!$role) {
                return response()->json([
                    'code_valide' => 404,
                    'message' => 'Le rôle spécifié n\'existe pas.'
                ], 404);
            }

            // Supprimer le rôle
            $role->delete();

            return response()->json([
                'code_valide' => 200,
                'message' => 'Le rôle a été supprimé avec succès.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la suppression du rôle.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
