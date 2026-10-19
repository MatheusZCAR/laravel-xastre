<?php

namespace App\Http\Controllers\Coordenacao;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\User;

class PainelController extends Controller
{
    public function __invoke()
    {
        return view('coordenacao.painel', [
            'totalCursos' => Curso::count(),
            'totalAlunos' => User::alunos()->count(),
            'totalProfessores' => User::professores()->count(),
            'semProfessor' => Curso::whereNull('professor_id')->orderBy('nome')->get(),
            'cursos' => Curso::with('professor')->withCount('alunos')->orderByDesc('alunos_count')->take(6)->get(),
            'ultimosAlunos' => User::alunos()->latest('id')->take(5)->get(),
        ]);
    }
}
