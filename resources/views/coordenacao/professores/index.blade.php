@extends('layouts.painel')

@section('titulo', 'Professores')

@section('conteudo')
<div class="sf-cabecalho">
    <div>
        <h1>Professores</h1>
        <p>Cadastre os professores; o curso de cada um é escolhido na tela do curso.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('coordenacao.professores.create') }}"><i class="bi bi-person-plus me-1" aria-hidden="true"></i>Cadastrar professor</a>
</div>

<section class="sf-folha">
    <div class="sf-folha-cab">
        @include('partials.busca', ['rotulo' => 'Buscar por nome, e-mail ou CPF', 'busca' => $busca])
        <span class="text-secondary small">{{ $professores->total() }} {{ $professores->total() === 1 ? 'professor' : 'professores' }}</span>
    </div>

    @if ($professores->isEmpty())
        <x-vazio icone="person-x">
            @if ($busca)
                <p>Nenhum professor encontrado para "{{ $busca }}".</p>
                <a class="btn btn-contorno btn-sm" href="{{ route('coordenacao.professores.index') }}">Limpar busca</a>
            @else
                <p>Nenhum professor cadastrado ainda.</p>
                <a class="btn btn-primary btn-sm" href="{{ route('coordenacao.professores.create') }}">Cadastrar o primeiro professor</a>
            @endif
        </x-vazio>
    @else
        <div class="table-responsive">
            <table class="sf-tabela">
                <thead><tr><th>Nome</th><th class="ocultar-celular">Telefone</th><th class="num">Cursos</th><th class="acoes ocultar-celular"><span class="visually-hidden">Ações</span></th></tr></thead>
                <tbody>
                    @foreach ($professores as $professor)
                        <tr>
                            <td><a class="sf-nome" href="{{ route('coordenacao.professores.show', $professor) }}">{{ $professor->name }}</a><span class="sf-sub">{{ $professor->email }}</span></td>
                            <td class="ocultar-celular">{{ $professor->telefone ?: '—' }}</td>
                            <td class="num">{{ $professor->cursos_lecionados_count }}</td>
                            <td class="acoes ocultar-celular"><a class="btn btn-contorno btn-sm" href="{{ route('coordenacao.professores.edit', $professor) }}" aria-label="Editar {{ $professor->name }}"><i class="bi bi-pencil" aria-hidden="true"></i><span class="d-none d-md-inline ms-1">Editar</span></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $professores->links('partials.paginacao') }}
    @endif
</section>
@endsection
