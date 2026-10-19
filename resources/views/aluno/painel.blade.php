@extends('layouts.painel')

@section('titulo', 'Meus cursos')

@section('conteudo')
<div class="sf-cabecalho">
    <div>
        <h1>Olá, {{ \Illuminate\Support\Str::words($aluno->name, 1, '') }}</h1>
        <p>Seu RA é {{ $aluno->ra }}.
            @if ($cursos->isNotEmpty())
                Você está em {{ $cursos->count() === 1 ? '1 curso' : $cursos->count().' cursos' }}, {{ $horas }} horas de aula.
            @endif
        </p>
    </div>
    <a class="btn btn-contorno" href="{{ route('perfil') }}"><i class="bi bi-person me-1" aria-hidden="true"></i>Meus dados e senha</a>
</div>

@if ($cursos->isEmpty())
    <section class="sf-folha">
        <x-vazio icone="journal"><p>Você ainda não está matriculado em nenhum curso. Fale com a secretaria para fazer sua matrícula.</p></x-vazio>
    </section>
@else
    @foreach ($cursos as $curso)
        <section class="sf-folha">
            <div class="sf-folha-cab">
                <h2>{{ $curso->nome }}</h2>
                <span class="sf-etiqueta">{{ $curso->carga_horaria }} horas</span>
            </div>
            <div class="sf-folha-corpo">
                <div class="row g-4">
                    <div class="col-md-7">
                        <p>{{ $curso->descricao }}</p>
                        <p class="mb-0 text-secondary">
                            <i class="bi bi-person-video3 me-1" aria-hidden="true"></i>
                            {{ $curso->professor ? 'Professor(a): '.$curso->professor->name : 'Professor a definir' }}
                        </p>
                    </div>
                    @if ($curso->conteudo)
                        <div class="col-md-5">
                            <h3 class="h6">O que você vai estudar</h3>
                            <ul class="mb-0 ps-3">
                                @foreach (preg_split('/\r?\n/', trim($curso->conteudo)) as $topico)
                                    @if (trim($topico) !== '')<li>{{ $topico }}</li>@endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endforeach
@endif
@endsection
