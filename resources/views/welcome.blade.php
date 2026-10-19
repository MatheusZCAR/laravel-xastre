@extends('layouts.base')

@section('corpo')
<header class="sf-ceu">
    <div class="container-xl sf-heroi">
        <div class="sf-heroi-topo">
            @include('partials.marca')
            @auth
                <a class="btn btn-claro btn-sm btn-outline-light" href="{{ route('painel') }}">Meu painel</a>
            @else
                <a class="btn btn-claro btn-sm btn-outline-light" href="{{ route('login') }}">Entrar</a>
            @endauth
        </div>

        @if (session('ok'))
            <div class="sf-aviso sf-aviso-ok" role="status"><i class="bi bi-check-circle-fill" aria-hidden="true"></i> {{ session('ok') }}</div>
        @endif

        <div class="sf-heroi-grade">
            <div>
                <h1>Aprenda tecnologia com a Star-Fox.</h1>
                <p>A Star-Fox Company é uma escola de cursos livres de tecnologia. Alunos, professores e coordenação acompanham tudo por aqui: cursos, turmas e matrículas.</p>
                <div class="d-flex flex-wrap gap-2">
                    @auth
                        <a class="btn btn-primary btn-lg" href="{{ route('painel') }}">Ir para o meu painel</a>
                    @else
                        <a class="btn btn-primary btn-lg" href="{{ route('login') }}">Entrar no portal</a>
                    @endauth
                    <a class="btn btn-claro btn-lg btn-outline-light" href="#cursos">Ver os cursos</a>
                </div>
                <p class="sf-heroi-nota">{{ $cursos->count() }} {{ $cursos->count() === 1 ? 'curso' : 'cursos' }} e {{ $totalAlunos }} {{ $totalAlunos === 1 ? 'aluno matriculado' : 'alunos matriculados' }}.</p>
            </div>
            <div class="sf-heroi-arte">
                @include('partials.constelacao')
            </div>
        </div>
    </div>
</header>

<main class="container-xl sf-catalogo" id="cursos">
    <div class="sf-catalogo-cab">
        <div>
            <h2>Cursos oferecidos</h2>
            <p>Para se matricular, procure a secretaria da escola.</p>
        </div>
    </div>

    @forelse ($cursos as $curso)
        <article class="sf-curso-linha">
            <h3>{{ $curso->nome }}</h3>
            <div class="sf-curso-horas">{{ $curso->carga_horaria }}h<small>de aula</small></div>
            <div>
                <p>{{ $curso->descricao }}</p>
                <div class="sf-curso-meta mt-1">
                    @if ($curso->professor)
                        <i class="bi bi-person-video3 me-1" aria-hidden="true"></i>Com {{ $curso->professor->name }}
                    @else
                        <i class="bi bi-hourglass-split me-1" aria-hidden="true"></i>Professor a definir
                    @endif
                </div>
            </div>
        </article>
    @empty
        <x-vazio icone="stars"><p>Nenhum curso cadastrado ainda. A coordenação pode criar o primeiro pelo painel.</p></x-vazio>
    @endforelse
</main>

<footer class="sf-rodape">Star-Fox Company &copy; {{ date('Y') }}</footer>
@endsection
