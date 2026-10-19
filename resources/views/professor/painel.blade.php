@extends('layouts.painel')

@section('titulo', 'Meus cursos')

@section('conteudo')
<div class="sf-cabecalho">
    <div>
        <h1>Olá, {{ \Illuminate\Support\Str::words(auth()->user()->name, 1, '') }}</h1>
        <p>
            @if ($cursos->isEmpty())
                Você ainda não tem cursos.
            @else
                Você dá aula em {{ $cursos->count() === 1 ? '1 curso' : $cursos->count().' cursos' }}, com {{ $totalAlunos }} {{ $totalAlunos === 1 ? 'aluno' : 'alunos' }} no total.
            @endif
        </p>
    </div>
</div>

<section class="sf-folha">
    <div class="sf-folha-cab"><h2>Meus cursos</h2></div>
    @if ($cursos->isEmpty())
        <x-vazio icone="journal"><p>Quando a coordenação colocar você como professor de um curso, ele aparece aqui.</p></x-vazio>
    @else
        <table class="sf-tabela">
            <thead><tr><th>Curso</th><th class="num">Carga horária</th><th class="num">Alunos</th></tr></thead>
            <tbody>
                @foreach ($cursos as $curso)
                    <tr>
                        <td><a class="sf-nome" href="{{ route('professor.curso', $curso) }}">{{ $curso->nome }}</a><span class="sf-sub">{{ \Illuminate\Support\Str::limit($curso->descricao, 90) }}</span></td>
                        <td class="num">{{ $curso->carga_horaria }}h</td>
                        <td class="num">{{ $curso->alunos_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</section>
@endsection
