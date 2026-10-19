<?php

namespace App\Http\Controllers\Coordenacao;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfessorRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ProfessorController extends Controller
{
    public function index(Request $request)
    {
        $professores = User::professores()
            ->busca($request->query('busca'))
            ->withCount('cursosLecionados')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('coordenacao.professores.index', ['professores' => $professores, 'busca' => $request->query('busca')]);
    }

    public function create()
    {
        return view('coordenacao.professores.form', ['professor' => new User(['papel' => User::PROFESSOR])]);
    }

    public function store(ProfessorRequest $request)
    {
        $professor = new User($request->dados());
        $professor->papel = User::PROFESSOR;
        $professor->save();

        return redirect()->route('coordenacao.professores.show', $professor)->with('ok', 'Professor(a) cadastrado(a).');
    }

    public function show(User $professor)
    {
        $professor->load(['cursosLecionados' => fn ($q) => $q->withCount('alunos')]);

        return view('coordenacao.professores.show', ['professor' => $professor]);
    }

    public function edit(User $professor)
    {
        return view('coordenacao.professores.form', ['professor' => $professor]);
    }

    public function update(ProfessorRequest $request, User $professor)
    {
        $professor->update($request->dados());

        return redirect()->route('coordenacao.professores.show', $professor)->with('ok', 'Cadastro atualizado.');
    }

    public function destroy(User $professor)
    {
        $cursos = $professor->cursosLecionados()->count();
        $professor->delete(); // os cursos dele ficam sem professor (nullOnDelete)

        $aviso = $cursos ? " {$cursos} curso(s) ficaram sem professor." : '';

        return redirect()->route('coordenacao.professores.index')->with('ok', "Cadastro de {$professor->name} excluído.{$aviso}");
    }
}
