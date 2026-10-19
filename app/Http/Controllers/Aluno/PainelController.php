<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PainelController extends Controller
{
    /** O aluno ve so os proprios cursos (nunca os dados de outros alunos). */
    public function __invoke(Request $request)
    {
        $aluno = $request->user();
        $cursos = $aluno->cursosMatriculados()->with('professor')->get();

        return view('aluno.painel', [
            'aluno' => $aluno,
            'cursos' => $cursos,
            'horas' => $cursos->sum('carga_horaria'),
        ]);
    }
}
