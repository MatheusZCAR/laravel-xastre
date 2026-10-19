@extends('layouts.painel')

@php $editando = $curso->exists; @endphp

@section('titulo', $editando ? 'Editar curso' : 'Novo curso')

@section('conteudo')
<a class="sf-voltar" href="{{ $editando ? route('coordenacao.cursos.show', $curso) : route('coordenacao.cursos.index') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i>{{ $editando ? $curso->nome : 'Cursos' }}</a>
<div class="sf-cabecalho">
    <h1>{{ $editando ? 'Editar curso' : 'Novo curso' }}</h1>
</div>

<form method="POST" action="{{ $editando ? route('coordenacao.cursos.update', $curso) : route('coordenacao.cursos.store') }}" class="sf-folha" novalidate>
    @csrf
    @if ($editando) @method('PUT') @endif

    <section class="sf-secao">
        <header><h2>Sobre o curso</h2><p>O nome e a descrição aparecem na página inicial do site.</p></header>
        <div>
            <div class="row">
                <x-campo class="col-md-8" nome="nome" rotulo="Nome do curso" :valor="$curso->nome" maxlength="120" obrigatorio />
                <x-campo class="col-md-4" nome="carga_horaria" rotulo="Carga horária (horas)" tipo="number" :valor="$curso->carga_horaria" min="1" max="2000" obrigatorio />
            </div>
            <div class="mb-3">
                <label class="form-label" for="descricao">Descrição<span class="text-danger" aria-hidden="true"> *</span></label>
                <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" rows="2" maxlength="500" required>{{ old('descricao', $curso->descricao) }}</textarea>
                @error('descricao')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="form-label" for="conteudo">Conteúdo</label>
                <textarea class="form-control @error('conteudo') is-invalid @enderror" id="conteudo" name="conteudo" rows="5" aria-describedby="conteudo-ajuda">{{ old('conteudo', $curso->conteudo) }}</textarea>
                @error('conteudo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text" id="conteudo-ajuda">Um tópico por linha.</div>
            </div>
        </div>
    </section>

    <section class="sf-secao">
        <header><h2>Professor</h2><p>Quem dá as aulas. O professor vê a turma no painel dele.</p></header>
        <div>
            <label class="form-label" for="professor_id">Professor responsável</label>
            <select class="form-select @error('professor_id') is-invalid @enderror" id="professor_id" name="professor_id">
                <option value="">Definir depois</option>
                @foreach ($professores as $professor)
                    <option value="{{ $professor->id }}" @selected((string) old('professor_id', $curso->professor_id) === (string) $professor->id)>{{ $professor->name }}</option>
                @endforeach
            </select>
            @error('professor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @if ($professores->isEmpty())
                <div class="form-text">Nenhum professor cadastrado. <a href="{{ route('coordenacao.professores.create') }}">Cadastre um professor</a> e volte aqui.</div>
            @endif
        </div>
    </section>

    <section class="sf-secao">
        <header><h2>Alunos matriculados</h2><p>Marque os alunos que fazem este curso.</p></header>
        <div>
            @include('partials.marcar', [
                'nome' => 'alunos', 'itens' => $alunos, 'marcados' => $matriculados, 'rotulo' => 'alunos',
                'descricao' => fn ($a) => 'RA '.$a->ra,
                'vazio' => 'Nenhum aluno cadastrado ainda.',
            ])
        </div>
    </section>

    <div class="sf-rodape-form">
        <a class="btn btn-contorno" href="{{ $editando ? route('coordenacao.cursos.show', $curso) : route('coordenacao.cursos.index') }}">Cancelar</a>
        <button type="submit" class="btn btn-primary">{{ $editando ? 'Salvar alterações' : 'Criar curso' }}</button>
    </div>
</form>
@endsection
