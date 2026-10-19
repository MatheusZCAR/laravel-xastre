@extends('layouts.painel')

@section('titulo', 'Cursos')

@section('conteudo')
<div class="sf-cabecalho">
    <div>
        <h1>Cursos</h1>
        <p>Crie cursos, escolha o professor e matricule os alunos.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('coordenacao.cursos.create') }}"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Novo curso</a>
</div>

<section class="sf-folha">
    <div class="sf-folha-cab">
        @include('partials.busca', ['rotulo' => 'Buscar curso pelo nome', 'busca' => $busca])
        <span class="text-secondary small">{{ $cursos->total() }} {{ $cursos->total() === 1 ? 'curso' : 'cursos' }}</span>
    </div>

    @if ($cursos->isEmpty())
        <x-vazio icone="journal-x">
            @if ($busca)
                <p>Nenhum curso encontrado para "{{ $busca }}".</p>
                <a class="btn btn-contorno btn-sm" href="{{ route('coordenacao.cursos.index') }}">Limpar busca</a>
            @else
                <p>Nenhum curso cadastrado ainda.</p>
                <a class="btn btn-primary btn-sm" href="{{ route('coordenacao.cursos.create') }}">Criar o primeiro curso</a>
            @endif
        </x-vazio>
    @else
        <div class="table-responsive">
            <table class="sf-tabela">
                <thead>
                    <tr><th>Curso</th><th class="ocultar-celular">Professor</th><th class="num ocultar-celular">Carga horária</th><th class="num">Alunos</th><th class="acoes ocultar-celular"><span class="visually-hidden">Ações</span></th></tr>
                </thead>
                <tbody>
                    @foreach ($cursos as $curso)
                        <tr>
                            <td>
                                <a class="sf-nome" href="{{ route('coordenacao.cursos.show', $curso) }}">{{ $curso->nome }}</a>
                                <span class="sf-sub">{{ \Illuminate\Support\Str::limit($curso->descricao, 80) }}</span>
                            </td>
                            <td class="ocultar-celular">
                                @if ($curso->professor)
                                    {{ $curso->professor->name }}
                                @else
                                    <span class="sf-etiqueta sf-etiqueta-alerta">Sem professor</span>
                                @endif
                            </td>
                            <td class="num ocultar-celular">{{ $curso->carga_horaria }}h</td>
                            <td class="num">{{ $curso->alunos_count }}</td>
                            <td class="acoes ocultar-celular">
                                <a class="btn btn-contorno btn-sm" href="{{ route('coordenacao.cursos.edit', $curso) }}" aria-label="Editar {{ $curso->nome }}"><i class="bi bi-pencil" aria-hidden="true"></i><span class="d-none d-md-inline ms-1">Editar</span></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $cursos->links('partials.paginacao') }}
    @endif
</section>
@endsection
