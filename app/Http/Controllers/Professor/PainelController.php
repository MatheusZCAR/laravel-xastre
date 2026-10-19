<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;

class PainelController extends Controller
{
    public function index(Request $request)
    {
        $cursos = $request->user()->cursosLecionados()->withCount('alunos')->get();

        return view('professor.painel', [
            'cursos' => $cursos,
            'totalAlunos' => $cursos->sum('alunos_count'),
        ]);
    }

    /** Turma de um curso: so o professor responsavel pode ver. */
    public function curso(Request $request, Curso $curso)
    {
        abort_unless($curso->professor_id === $request->user()->id, 403, 'Este curso não é seu.');

        $curso->load('alunos');

        return view('professor.curso', ['curso' => $curso]);
    }
}
