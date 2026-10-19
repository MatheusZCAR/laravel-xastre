<?php

namespace App\Http\Controllers\Coordenacao;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlunoRequest;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlunoController extends Controller
{
    public function index(Request $request)
    {
        $alunos = User::alunos()
            ->busca($request->query('busca'))
            ->withCount('cursosMatriculados')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('coordenacao.alunos.index', ['alunos' => $alunos, 'busca' => $request->query('busca')]);
    }

    public function create()
    {
        return view('coordenacao.alunos.form', $this->opcoes(new User(['papel' => User::ALUNO])));
    }

    public function store(AlunoRequest $request)
    {
        $aluno = DB::transaction(function () use ($request) {
            $aluno = new User($request->dados());
            $aluno->papel = User::ALUNO; // o papel nunca vem do formulario
            $aluno->save();
            $aluno->cursosMatriculados()->sync($request->input('cursos', []));

            return $aluno;
        });

        return redirect()->route('coordenacao.alunos.show', $aluno)
            ->with('ok', "Aluno(a) cadastrado(a) com o RA {$aluno->ra}.");
    }

    public function show(User $aluno)
    {
        $aluno->load('cursosMatriculados.professor');

        return view('coordenacao.alunos.show', ['aluno' => $aluno]);
    }

    public function edit(User $aluno)
    {
        $aluno->load('cursosMatriculados');

        return view('coordenacao.alunos.form', $this->opcoes($aluno));
    }

    public function update(AlunoRequest $request, User $aluno)
    {
        DB::transaction(function () use ($request, $aluno) {
            $aluno->update($request->dados());
            $aluno->cursosMatriculados()->sync($request->input('cursos', []));
        });

        return redirect()->route('coordenacao.alunos.show', $aluno)->with('ok', 'Cadastro atualizado.');
    }

    public function destroy(User $aluno)
    {
        $aluno->delete();

        return redirect()->route('coordenacao.alunos.index')->with('ok', "Cadastro de {$aluno->name} excluído.");
    }

    private function opcoes(User $aluno): array
    {
        return [
            'aluno' => $aluno,
            'cursos' => Curso::orderBy('nome')->get(['id', 'nome', 'carga_horaria']),
            'matriculas' => old('cursos', $aluno->exists ? $aluno->cursosMatriculados->pluck('id')->all() : []),
            'ufs' => \App\Http\Requests\AlunoRequest::UFS,
        ];
    }
}
