<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\User;

class HomeController extends Controller
{
    /** Pagina inicial publica: apresenta a escola e os cursos oferecidos. */
    public function index()
    {
        $cursos = Curso::with('professor')->withCount('alunos')->orderBy('nome')->get();

        return view('welcome', [
            'cursos' => $cursos,
            'totalAlunos' => User::alunos()->count(),
        ]);
    }

    /** /painel leva cada pessoa ao painel do seu papel. */
    public function painel()
    {
        return redirect(auth()->user()->rotaPainel());
    }
}
