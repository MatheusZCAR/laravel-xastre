@extends('layouts.base')

@section('titulo', 'Sem acesso')

@section('corpo')
<main class="sf-ceu sf-erro">
    <div>
        <strong>403</strong>
        <h1 class="text-white mt-2">Sem acesso</h1>
        <p>Esta página não está disponível para o seu perfil.</p>
        @auth
            <a class="btn btn-primary" href="{{ route('painel') }}">Ir para o meu painel</a>
        @else
            <a class="btn btn-primary" href="{{ url('/') }}">Voltar para o início</a>
        @endauth
    </div>
</main>
@endsection
