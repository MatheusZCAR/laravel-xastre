@extends('layouts.painel')

@section('titulo', 'Painel da coordenação')

@section('conteudo')
<div class="sf-cabecalho">
    <div>
        <h1>Olá, {{ \Illuminate\Support\Str::words(auth()->user()->name, 1, '') }}</h1>
        <p>Resumo da escola hoje.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-contorno" href="{{ route('coordenacao.alunos.create') }}"><i class="bi bi-person-plus me-1" aria-hidden="true"></i>Cadastrar aluno</a>
        <a class="btn btn-primary" href="{{ route('coordenacao.cursos.create') }}"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Novo curso</a>
    </div>
</div>

<nav class="sf-quadro" aria-label="Resumo">
    <a href="{{ route('coordenacao.cursos.index') }}"><strong>{{ $totalCursos }}</strong><span>Cursos</span></a>
    <a href="{{ route('coordenacao.alunos.index') }}"><strong>{{ $totalAlunos }}</strong><span>Alunos</span></a>
    <a href="{{ route('coordenacao.professores.index') }}"><strong>{{ $totalProfessores }}</strong><span>Professores</span></a>
</nav>

@if ($semProfessor->isNotEmpty())
    <div class="sf-aviso sf-aviso-erro fw-normal">
        <i class="bi bi-person-exclamation" aria-hidden="true"></i>
        <span>
            <strong>{{ $semProfessor->count() === 1 ? '1 curso está' : $semProfessor->count().' cursos estão' }} sem professor:</strong>
            @foreach ($semProfessor as $curso)
                <a href="{{ route('coordenacao.cursos.edit', $curso) }}">{{ $curso->nome }}</a>@if (! $loop->last), @endif
            @endforeach
        </span>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-7">
        <section class="sf-folha h-100">
            <div class="sf-folha-cab">
                <h2>Cursos com mais alunos</h2>
                <a href="{{ route('coordenacao.cursos.index') }}">Ver todos</a>
            </div>
            @if ($cursos->isEmpty())
                <x-vazio icone="journal-plus"><p>Nenhum curso cadastrado.</p><a class="btn btn-primary btn-sm" href="{{ route('coordenacao.cursos.create') }}">Criar o primeiro curso</a></x-vazio>
            @else
                <table class="sf-tabela">
                    <thead><tr><th>Curso</th><th class="ocultar-celular">Professor</th><th class="num">Alunos</th></tr></thead>
                    <tbody>
                        @foreach ($cursos as $curso)
                            <tr>
                                <td><a class="sf-nome" href="{{ route('coordenacao.cursos.show', $curso) }}">{{ $curso->nome }}</a></td>
                                <td class="ocultar-celular">{{ $curso->professor->name ?? '—' }}</td>
                                <td class="num">{{ $curso->alunos_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </div>
    <div class="col-lg-5">
        <section class="sf-folha h-100">
            <div class="sf-folha-cab">
                <h2>Últimos alunos cadastrados</h2>
                <a href="{{ route('coordenacao.alunos.index') }}">Ver todos</a>
            </div>
            @if ($ultimosAlunos->isEmpty())
                <x-vazio icone="people"><p>Nenhum aluno cadastrado.</p></x-vazio>
            @else
                <table class="sf-tabela">
                    <tbody>
                        @foreach ($ultimosAlunos as $aluno)
                            <tr>
                                <td><a class="sf-nome" href="{{ route('coordenacao.alunos.show', $aluno) }}">{{ $aluno->name }}</a><span class="sf-sub">RA {{ $aluno->ra }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </div>
</div>
@endsection
