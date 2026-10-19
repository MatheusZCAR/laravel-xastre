@extends('layouts.base')

@section('titulo', 'Algo deu errado')

@section('corpo')
<main class="sf-ceu sf-erro">
    <div>
        <strong>500</strong>
        <h1 class="text-white mt-2">Algo deu errado</h1>
        <p>Tivemos um problema no servidor. Tente de novo em alguns minutos.</p>
        @auth
            <a class="btn btn-primary" href="{{ route('painel') }}">Ir para o meu painel</a>
        @else
            <a class="btn btn-primary" href="{{ url('/') }}">Voltar para o início</a>
        @endauth
    </div>
</main>
@endsection
