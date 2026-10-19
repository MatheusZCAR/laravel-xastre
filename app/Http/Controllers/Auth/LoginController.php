<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        // confere e-mail e senha (com limite de 5 tentativas por minuto)
        $request->authenticate();

        // nova sessao a cada login, para evitar sequestro de sessao
        $request->session()->regenerate();

        return redirect()->intended($request->user()->rotaPainel());
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('ok', 'Você saiu do portal.');
    }
}
