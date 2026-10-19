@extends('layouts.painel')

@php $editando = $aluno->exists; @endphp

@section('titulo', $editando ? 'Editar aluno' : 'Cadastrar aluno')

@section('conteudo')
<a class="sf-voltar" href="{{ $editando ? route('coordenacao.alunos.show', $aluno) : route('coordenacao.alunos.index') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i>{{ $editando ? $aluno->name : 'Alunos' }}</a>
<div class="sf-cabecalho">
    <div>
        <h1>{{ $editando ? 'Editar aluno' : 'Cadastrar aluno' }}</h1>
        @if ($editando)<p>RA {{ $aluno->ra }}</p>@endif
    </div>
</div>

<form method="POST" action="{{ $editando ? route('coordenacao.alunos.update', $aluno) : route('coordenacao.alunos.store') }}" class="sf-folha" novalidate>
    @csrf
    @if ($editando) @method('PUT') @endif

    @include('partials.campos-pessoa', ['pessoa' => $aluno, 'editando' => $editando, 'extraAluno' => true])

    <section class="sf-secao">
        <header><h2>Endereço</h2><p>Digite o CEP e o resto se preenche sozinho.</p></header>
        <div>
            <div class="row">
                <x-campo class="col-md-4" nome="cep" rotulo="CEP" :valor="$aluno->cep_formatado" data-mascara="cep" data-cep="cep-aviso" inputmode="numeric" placeholder="00000-000" />
                <div class="col-md-8 d-flex align-items-md-center pb-3 pt-md-3"><span class="form-text m-0" id="cep-aviso" aria-live="polite"></span></div>
            </div>
            <div class="row">
                <x-campo class="col-md-9" nome="rua" rotulo="Rua" :valor="$aluno->rua" />
                <x-campo class="col-md-3" nome="numero" rotulo="Número" :valor="$aluno->numero" />
            </div>
            <div class="row">
                <x-campo class="col-md-5" nome="bairro" rotulo="Bairro" :valor="$aluno->bairro" />
                <x-campo class="col-md-5" nome="cidade" rotulo="Cidade" :valor="$aluno->cidade" />
                <div class="col-md-2 mb-3">
                    <label class="form-label" for="campo-uf">Estado</label>
                    <select class="form-select @error('uf') is-invalid @enderror" id="campo-uf" name="uf">
                        <option value=""></option>
                        @foreach ($ufs as $uf)
                            <option @selected(old('uf', $aluno->uf) === $uf)>{{ $uf }}</option>
                        @endforeach
                    </select>
                    @error('uf')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </section>

    <section class="sf-secao">
        <header><h2>Cursos</h2><p>Marque os cursos em que o aluno está matriculado.</p></header>
        <div>
            @include('partials.marcar', [
                'nome' => 'cursos', 'itens' => $cursos, 'marcados' => $matriculas, 'rotulo' => 'cursos',
                'descricao' => fn ($c) => $c->carga_horaria.'h',
                'vazio' => 'Nenhum curso cadastrado ainda.',
            ])
        </div>
    </section>

    <div class="sf-rodape-form">
        <a class="btn btn-contorno" href="{{ $editando ? route('coordenacao.alunos.show', $aluno) : route('coordenacao.alunos.index') }}">Cancelar</a>
        <button type="submit" class="btn btn-primary">{{ $editando ? 'Salvar alterações' : 'Cadastrar aluno' }}</button>
    </div>
</form>
@endsection
