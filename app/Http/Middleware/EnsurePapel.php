<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Libera a rota so para os papeis informados. Uso: ->middleware('papel:coordenacao')
 * Deve vir depois do middleware "auth".
 */
class EnsurePapel
{
    public function handle(Request $request, Closure $next, string ...$papeis)
    {
        $usuario = $request->user();

        if (! $usuario || ! in_array($usuario->papel, $papeis, true)) {
            abort(403, 'Esta área não está disponível para o seu perfil.');
        }

        return $next($request);
    }
}
