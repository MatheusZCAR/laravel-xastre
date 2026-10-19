@extends('layouts.base')

@section('titulo', 'Página não encontrada')

@section('corpo')
<main class="sf-ceu sf-erro">
    <div>
        <strong>404</strong>
        <h1 class="text-white mt-2">Página não encontrada</h1>
        <p>O endereço pode ter mudado ou o cadastro foi excluído.</p>
        @auth
            <a class="btn btn-primary" href="{{ route('painel') }}">Ir para o meu painel</a>
        @else
            <a class="btn btn-primary" href="{{ url('/') }}">Voltar para o início</a>
        @endauth
    </div>
</main>
@endsection
