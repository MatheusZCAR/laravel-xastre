@extends('layouts.base')

@php
    $usuario = auth()->user();
    $menu = match ($usuario->papel) {
        'coordenacao' => [
            ['coordenacao.painel', 'Painel', 'coordenacao.painel'],
            ['coordenacao.cursos.index', 'Cursos', 'coordenacao.cursos.*'],
            ['coordenacao.alunos.index', 'Alunos', 'coordenacao.alunos.*'],
            ['coordenacao.professores.index', 'Professores', 'coordenacao.professores.*'],
        ],
        'professor' => [
            ['professor.painel', 'Meus cursos', 'professor.*'],
        ],
        default => [
            ['aluno.painel', 'Meus cursos', 'aluno.*'],
        ],
    };
@endphp

@section('corpo')
<header class="sf-topo">
    <nav class="navbar navbar-expand-lg navbar-dark" aria-label="Menu principal">
        <div class="container-xl">
            @include('partials.marca')
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Abrir menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-lg-4 me-auto">
                    @foreach ($menu as [$rota, $rotulo, $padrao])
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs($padrao) ? 'active' : '' }}" href="{{ route($rota) }}" @if (request()->routeIs($padrao)) aria-current="page" @endif>{{ $rotulo }}</a>
                        </li>
                    @endforeach
                </ul>
                <div class="dropdown mt-2 mt-lg-0">
                    <button class="btn d-flex align-items-center gap-2 text-white p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="sf-avatar" aria-hidden="true">{{ $usuario->iniciais }}</span>
                        <span class="text-start lh-sm">
                            <span class="d-block">{{ \Illuminate\Support\Str::words($usuario->name, 2, '') }}</span>
                            <small class="fw-normal opacity-75">{{ $usuario->papel_nome }}</small>
                        </span>
                        <i class="bi bi-chevron-down small" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('perfil') }}"><i class="bi bi-person me-2" aria-hidden="true"></i>Meu perfil e senha</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item" type="submit"><i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i>Sair</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>

<main class="sf-pagina" id="conteudo">
    @include('partials.avisos')
    @yield('conteudo')
</main>

<footer class="sf-rodape">Star-Fox Company &copy; {{ date('Y') }}</footer>
@endsection
