@extends('layouts.base')

@section('titulo', 'A página expirou')

@section('corpo')
<main class="sf-ceu sf-erro">
    <div>
        <strong>419</strong>
        <h1 class="text-white mt-2">A página expirou</h1>
        <p>Você ficou um tempo sem mexer. Volte, recarregue a página e tente de novo.</p>
        @auth
            <a class="btn btn-primary" href="{{ route('painel') }}">Ir para o meu painel</a>
        @else
            <a class="btn btn-primary" href="{{ url('/') }}">Voltar para o início</a>
        @endauth
    </div>
</main>
@endsection
