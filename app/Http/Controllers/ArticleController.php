<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Récupérer la liste des articles
            $articles = Article::all();

            return response()->json([
                'code_valide' => 200,
                'Liste_de_tous_les_articles' => $articles,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code_valide' => 500,
                'message' => 'Une erreur est survenue lors de la récupération des articles.',
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
        
        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return response()->json([
                'code_valide' => 401,
                'message' => 'Vous devez être connecté pour ajouter un article.'
            ], 401);
        }
        
        // Vérifier si l'utilisateur a le rôle et le poste requis
        if ($user->role && $user->personnelRestaurant && $user->role->nom_role == 'personnel restaurant' && $user->personnelRestaurant->poste_occupe == 'Gérant') {
            
            // Vérifier si le champ 'image' est présent dans la requête
            if (!$request->hasFile('image')) {
                return response()->json([
                    'code_valide' => 400,
                    'message' => 'L\'image de l\'article est requise.'
                ], 400);
            }
            
            // Gérer l'upload de l'image
            $imagePath = $request->file('image')->store('images', 'public');
            
            // Créer un nouvel article
            $article = new Article([
                'nom_article' => $request->nom_article,
                'description_article' => $request->description_article, // Ajout de la description de l'article
                'prix_article' => $request->prix_article, // Ajout du prix de l'article
                'image' => $imagePath, // Ajout du chemin de l'image
                'personnel_restaurant_id' => $user->personnelRestaurant->id, // Ajout de l'ID du personnel de restaurant associé
            ]);
            
            // Sauvegarder l'article
            $article->save();
            
            return response()->json([
                'code_valide' => 201,
                'message' => 'L\'article a été ajouté avec succès.',
            ], 201);
        } else {
            return response()->json([
                'code_valide' => 403,
                'message' => 'Vous n\'avez pas les autorisations nécessaires pour ajouter un article.'
            ], 403);
        }
    } catch (\Exception $e) {
        return response()->json([
            'code_valide' => 500,
            'message' => 'Une erreur est survenue lors de l\'ajout de l\'article.',
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
        // Trouver l'article par son identifiant
        $article = Article::find($id);

        // Vérifier si l'article existe
        if (!$article) {
            return response()->json([
                'code_valide' => 404,
                'message' => 'L\'article que vous essayiez de trouver n\'existe pas, veuillez créer d\'abord l\'article.'
            ], 404);
        }

        return response()->json([
            'code_valide' => 200,
            'Les_détails_d\'un_article' => $article,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'code_valide' => 500,
            'message' => 'Une erreur est survenue lors de la récupération des détails de l\'article.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, $id)
     {
         try {
             $article = Article::findOrFail($id);
             $user = Auth::user();
             
             if ($user->id !== $article->user_id && !$user->role->nom_role == 'admin') {
                 return response()->json([
                     'code_valide' => 403,
                     'message' => 'Vous n\'êtes pas autorisé à mettre à jour cet article.'
                 ], 403);
             }
     
             $article->update([
                 'nom_article' => $request->nom_article,
                 'description_article' => $request->description_article,
                 'prix_article' => $request->prix_article,
             ]);
     
             if ($request->hasFile('image')) {
                 if ($article->image) {
                     Storage::disk('public')->delete($article->image);
                 }
                 
                 $imagePath = $request->file('image')->store('images', 'public');
                 $article->image = $imagePath;
             }
     
             $article->save();
     
             return response()->json([
                'code_valide' => 200,
                 'message' => 'Article mis à jour avec succès.',
                 'article' => $article,
             ], 200);
         } catch (\Exception $e) {
             return response()->json([
                'code_valide' => 500,
                 'message' => 'Une erreur est survenue lors de la mise à jour de l\'article.',
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
            // Trouver l'article par son identifiant
            $article = Article::findOrFail($id);

            // Vérifier si l'utilisateur actuel est l'auteur de l'article ou s'il a le rôle d'administrateur
            $user = Auth::user();
            if ($user->id !== $article->user_id && !$user->role->nom_role == 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à supprimer cet article.',
                ], 403);
            }

            // Supprimer l'image associée à l'article s'il en a une
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }

            // Supprimer l'article de la base de données
            $article->delete();

            return response()->json([
                'code_valide' => 200,
                'message' => 'Article supprimé avec succès.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code_valide' => 500,
                'message' => 'Une erreur est survenue lors de la suppression de l\'article.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
