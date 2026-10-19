@extends('layouts.painel')

@section('titulo', $professor->name)

@section('conteudo')
<a class="sf-voltar" href="{{ route('coordenacao.professores.index') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i>Professores</a>
<div class="sf-cabecalho">
    <div class="d-flex align-items-center gap-3">
        <span class="sf-avatar sf-avatar-g" aria-hidden="true">{{ $professor->iniciais }}</span>
        <div><h1>{{ $professor->name }}</h1><p class="m-0">Professor(a)</p></div>
    </div>
    <div class="d-flex flex-wrap gap-2">
        @include('partials.excluir', [
            'acao' => route('coordenacao.professores.destroy', $professor),
            'pergunta' => $professor->cursosLecionados->isEmpty()
                ? "Excluir o cadastro de {$professor->name}?"
                : "Excluir o cadastro de {$professor->name}? Os {$professor->cursosLecionados->count()} cursos dele ficarão sem professor.",
            'rotulo' => 'Excluir',
        ])
        <a class="btn btn-primary" href="{{ route('coordenacao.professores.edit', $professor) }}"><i class="bi bi-pencil me-1" aria-hidden="true"></i>Editar cadastro</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">@include('partials.ficha-pessoa', ['pessoa' => $professor])</div>
    <div class="col-lg-7">
        <section class="sf-folha">
            <div class="sf-folha-cab"><h2>Cursos que leciona</h2></div>
            @if ($professor->cursosLecionados->isEmpty())
                <x-vazio icone="journal"><p>Nenhum curso com este professor. Escolha o professor na tela de edição do curso.</p><a class="btn btn-contorno btn-sm" href="{{ route('coordenacao.cursos.index') }}">Ir para os cursos</a></x-vazio>
            @else
                <table class="sf-tabela">
                    <thead><tr><th>Curso</th><th class="num">Horas</th><th class="num">Alunos</th></tr></thead>
                    <tbody>
                        @foreach ($professor->cursosLecionados as $curso)
                            <tr>
                                <td><a class="sf-nome" href="{{ route('coordenacao.cursos.show', $curso) }}">{{ $curso->nome }}</a></td>
                                <td class="num">{{ $curso->carga_horaria }}h</td>
                                <td class="num">{{ $curso->alunos_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </div>
</div>
@endsection
