@extends('layouts.painel')

@section('titulo', $curso->nome)

@section('conteudo')
<a class="sf-voltar" href="{{ route('professor.painel') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i>Meus cursos</a>
<div class="sf-cabecalho">
    <div>
        <h1>{{ $curso->nome }}</h1>
        <p>{{ $curso->carga_horaria }} horas. {{ $curso->descricao }}</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <section class="sf-folha">
            <div class="sf-folha-cab"><h2>Turma</h2><span class="text-secondary small">{{ $curso->alunos->count() }} {{ $curso->alunos->count() === 1 ? 'aluno' : 'alunos' }}</span></div>
            @if ($curso->alunos->isEmpty())
                <x-vazio icone="people"><p>Nenhum aluno matriculado ainda.</p></x-vazio>
            @else
                <div class="table-responsive">
                    <table class="sf-tabela">
                        <thead><tr><th>Aluno</th><th>RA</th><th class="ocultar-celular">E-mail</th></tr></thead>
                        <tbody>
                            @foreach ($curso->alunos as $aluno)
                                <tr><td class="fw-bold">{{ $aluno->name }}</td><td>{{ $aluno->ra }}</td><td class="ocultar-celular"><a href="mailto:{{ $aluno->email }}">{{ $aluno->email }}</a></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
    <div class="col-lg-4">
        <section class="sf-folha">
            <div class="sf-folha-cab"><h2>Conteúdo</h2></div>
            <div class="sf-folha-corpo">
                @if ($curso->conteudo)
                    <ul class="mb-0 ps-3">
                        @foreach (preg_split('/\r?\n/', trim($curso->conteudo)) as $topico)
                            @if (trim($topico) !== '')<li>{{ $topico }}</li>@endif
                        @endforeach
                    </ul>
                @else
                    <p class="text-secondary mb-0">A coordenação ainda não cadastrou o conteúdo.</p>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
