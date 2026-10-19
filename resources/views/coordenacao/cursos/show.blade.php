@extends('layouts.painel')

@section('titulo', $curso->nome)

@section('conteudo')
<a class="sf-voltar" href="{{ route('coordenacao.cursos.index') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i>Cursos</a>
<div class="sf-cabecalho">
    <div>
        <h1>{{ $curso->nome }}</h1>
        <p>{{ $curso->descricao }}</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        @include('partials.excluir', [
            'acao' => route('coordenacao.cursos.destroy', $curso),
            'pergunta' => "Excluir o curso \"{$curso->nome}\"? As {$curso->alunos->count()} matrículas dele também serão apagadas.",
            'rotulo' => 'Excluir',
        ])
        <a class="btn btn-primary" href="{{ route('coordenacao.cursos.edit', $curso) }}"><i class="bi bi-pencil me-1" aria-hidden="true"></i>Editar curso</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <section class="sf-folha">
            <div class="sf-folha-cab"><h2>Dados do curso</h2></div>
            <div class="sf-folha-corpo">
                <dl class="sf-ficha">
                    <dt>Carga horária</dt><dd>{{ $curso->carga_horaria }} horas</dd>
                    <dt>Professor</dt>
                    <dd>
                        @if ($curso->professor)
                            <a href="{{ route('coordenacao.professores.show', $curso->professor) }}">{{ $curso->professor->name }}</a>
                        @else
                            <span class="sf-etiqueta sf-etiqueta-alerta">Sem professor</span>
                        @endif
                    </dd>
                    <dt>Alunos</dt><dd>{{ $curso->alunos->count() }}</dd>
                </dl>
                @if ($curso->conteudo)
                    <h3 class="h6 mt-4 mb-2">Conteúdo</h3>
                    <ul class="mb-0 ps-3">
                        @foreach (preg_split('/\r?\n/', trim($curso->conteudo)) as $topico)
                            @if (trim($topico) !== '')<li>{{ $topico }}</li>@endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>
    </div>
    <div class="col-lg-8">
        <section class="sf-folha">
            <div class="sf-folha-cab">
                <h2>Turma</h2>
                <a class="btn btn-contorno btn-sm" href="{{ route('coordenacao.cursos.edit', $curso) }}#filtro-alunos"><i class="bi bi-person-plus me-1" aria-hidden="true"></i>Matricular alunos</a>
            </div>
            @if ($curso->alunos->isEmpty())
                <x-vazio icone="people"><p>Nenhum aluno matriculado neste curso.</p></x-vazio>
            @else
                <div class="table-responsive">
                    <table class="sf-tabela">
                        <thead><tr><th>Aluno</th><th>RA</th><th class="ocultar-celular">E-mail</th></tr></thead>
                        <tbody>
                            @foreach ($curso->alunos as $aluno)
                                <tr>
                                    <td><a class="sf-nome" href="{{ route('coordenacao.alunos.show', $aluno) }}">{{ $aluno->name }}</a></td>
                                    <td>{{ $aluno->ra }}</td>
                                    <td class="ocultar-celular">{{ $aluno->email }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
