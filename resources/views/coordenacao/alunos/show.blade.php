@extends('layouts.painel')

@section('titulo', $aluno->name)

@section('conteudo')
<a class="sf-voltar" href="{{ route('coordenacao.alunos.index') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i>Alunos</a>
<div class="sf-cabecalho">
    <div class="d-flex align-items-center gap-3">
        <span class="sf-avatar sf-avatar-g" aria-hidden="true">{{ $aluno->iniciais }}</span>
        <div><h1>{{ $aluno->name }}</h1><p class="m-0">RA {{ $aluno->ra }}</p></div>
    </div>
    <div class="d-flex flex-wrap gap-2">
        @include('partials.excluir', [
            'acao' => route('coordenacao.alunos.destroy', $aluno),
            'pergunta' => "Excluir o cadastro de {$aluno->name}? As matrículas dele também serão apagadas.",
            'rotulo' => 'Excluir',
        ])
        <a class="btn btn-primary" href="{{ route('coordenacao.alunos.edit', $aluno) }}"><i class="bi bi-pencil me-1" aria-hidden="true"></i>Editar cadastro</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        @include('partials.ficha-pessoa', ['pessoa' => $aluno])
    </div>
    <div class="col-lg-7">
        <section class="sf-folha">
            <div class="sf-folha-cab">
                <h2>Cursos</h2>
                <span class="text-secondary small">{{ $aluno->cursosMatriculados->sum('carga_horaria') }} horas no total</span>
            </div>
            @if ($aluno->cursosMatriculados->isEmpty())
                <x-vazio icone="journal"><p>Este aluno ainda não está em nenhum curso.</p><a class="btn btn-contorno btn-sm" href="{{ route('coordenacao.alunos.edit', $aluno) }}">Matricular em um curso</a></x-vazio>
            @else
                <table class="sf-tabela">
                    <thead><tr><th>Curso</th><th class="ocultar-celular">Professor</th><th class="num">Horas</th></tr></thead>
                    <tbody>
                        @foreach ($aluno->cursosMatriculados as $curso)
                            <tr>
                                <td><a class="sf-nome" href="{{ route('coordenacao.cursos.show', $curso) }}">{{ $curso->nome }}</a></td>
                                <td class="ocultar-celular">{{ $curso->professor->name ?? 'A definir' }}</td>
                                <td class="num">{{ $curso->carga_horaria }}h</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </div>
</div>
@endsection
