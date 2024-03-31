<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class MiddlewareUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        // Récupérer l'utilisateur actuel
        $user = $request->user();
        
        // Vérifier si l'utilisateur a un rôle et si ce rôle correspond au rôle requis
        if ($user && $user->role && $user->role->nom_role === $role) {
            return $next($request);
        }
        
        // Si l'utilisateur n'a pas le rôle requis, retourner un accès refusé
        return response()->json('Accès refusé.', 403);
    }
}
