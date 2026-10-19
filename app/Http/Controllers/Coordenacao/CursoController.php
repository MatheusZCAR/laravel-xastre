<?php

namespace App\Http\Controllers\Coordenacao;

use App\Http\Controllers\Controller;
use App\Http\Requests\CursoRequest;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CursoController extends Controller
{
    public function index(Request $request)
    {
        $cursos = Curso::busca($request->query('busca'))
            ->with('professor')
            ->withCount('alunos')
            ->orderBy('nome')
            ->paginate(10)
            ->withQueryString();

        return view('coordenacao.cursos.index', ['cursos' => $cursos, 'busca' => $request->query('busca')]);
    }

    public function create()
    {
        return view('coordenacao.cursos.form', $this->opcoes(new Curso()));
    }

    public function store(CursoRequest $request)
    {
        $curso = DB::transaction(function () use ($request) {
            $curso = Curso::create($request->safe()->except('alunos'));
            $curso->alunos()->sync($request->input('alunos', []));

            return $curso;
        });

        return redirect()->route('coordenacao.cursos.show', $curso)->with('ok', 'Curso criado.');
    }

    public function show(Curso $curso)
    {
        $curso->load(['professor', 'alunos']);

        return view('coordenacao.cursos.show', ['curso' => $curso]);
    }

    public function edit(Curso $curso)
    {
        $curso->load('alunos');

        return view('coordenacao.cursos.form', $this->opcoes($curso));
    }

    public function update(CursoRequest $request, Curso $curso)
    {
        DB::transaction(function () use ($request, $curso) {
            $curso->update($request->safe()->except('alunos'));
            $curso->alunos()->sync($request->input('alunos', []));
        });

        return redirect()->route('coordenacao.cursos.show', $curso)->with('ok', 'Curso atualizado.');
    }

    public function destroy(Curso $curso)
    {
        $curso->delete(); // as matriculas saem junto (cascade)

        return redirect()->route('coordenacao.cursos.index')->with('ok', "Curso \"{$curso->nome}\" excluído.");
    }

    private function opcoes(Curso $curso): array
    {
        return [
            'curso' => $curso,
            'professores' => User::professores()->orderBy('name')->get(['id', 'name']),
            'alunos' => User::alunos()->orderBy('name')->get(['id', 'name', 'ra']),
            'matriculados' => old('alunos', $curso->exists ? $curso->alunos->pluck('id')->all() : []),
        ];
    }
}
