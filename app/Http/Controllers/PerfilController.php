<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class PerfilController extends Controller
{
    public function show(Request $request)
    {
        $usuario = $request->user();
        $usuario->load($usuario->isProfessor() ? 'cursosLecionados' : 'cursosMatriculados');

        return view('perfil.show', ['usuario' => $usuario]);
    }

    public function senha(Request $request)
    {
        $request->validate([
            'senha_atual' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8), 'different:senha_atual'],
        ], [
            'password.different' => 'A nova senha precisa ser diferente da atual.',
        ], [
            'password' => 'nova senha',
        ]);

        $request->user()->update(['password' => $request->password]);

        return back()->with('ok', 'Senha alterada.');
    }
}
