@extends('layouts.painel')

@section('titulo', 'Alunos')

@section('conteudo')
<div class="sf-cabecalho">
    <div>
        <h1>Alunos</h1>
        <p>Cadastre alunos e escolha os cursos de cada um. O RA é gerado automaticamente.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('coordenacao.alunos.create') }}"><i class="bi bi-person-plus me-1" aria-hidden="true"></i>Cadastrar aluno</a>
</div>

<section class="sf-folha">
    <div class="sf-folha-cab">
        @include('partials.busca', ['rotulo' => 'Buscar por nome, e-mail, CPF ou RA', 'busca' => $busca])
        <span class="text-secondary small">{{ $alunos->total() }} {{ $alunos->total() === 1 ? 'aluno' : 'alunos' }}</span>
    </div>

    @if ($alunos->isEmpty())
        <x-vazio icone="person-x">
            @if ($busca)
                <p>Nenhum aluno encontrado para "{{ $busca }}".</p>
                <a class="btn btn-contorno btn-sm" href="{{ route('coordenacao.alunos.index') }}">Limpar busca</a>
            @else
                <p>Nenhum aluno cadastrado ainda.</p>
                <a class="btn btn-primary btn-sm" href="{{ route('coordenacao.alunos.create') }}">Cadastrar o primeiro aluno</a>
            @endif
        </x-vazio>
    @else
        <div class="table-responsive">
            <table class="sf-tabela">
                <thead><tr><th>Nome</th><th>RA</th><th class="ocultar-celular">CPF</th><th class="num">Cursos</th><th class="acoes ocultar-celular"><span class="visually-hidden">Ações</span></th></tr></thead>
                <tbody>
                    @foreach ($alunos as $aluno)
                        <tr>
                            <td><a class="sf-nome" href="{{ route('coordenacao.alunos.show', $aluno) }}">{{ $aluno->name }}</a><span class="sf-sub">{{ $aluno->email }}</span></td>
                            <td>{{ $aluno->ra }}</td>
                            <td class="ocultar-celular">{{ $aluno->cpf_formatado }}</td>
                            <td class="num">{{ $aluno->cursos_matriculados_count }}</td>
                            <td class="acoes ocultar-celular"><a class="btn btn-contorno btn-sm" href="{{ route('coordenacao.alunos.edit', $aluno) }}" aria-label="Editar {{ $aluno->name }}"><i class="bi bi-pencil" aria-hidden="true"></i><span class="d-none d-md-inline ms-1">Editar</span></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $alunos->links('partials.paginacao') }}
    @endif
</section>
@endsection
