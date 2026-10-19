@extends('layouts.painel')

@section('titulo', 'Meu perfil')

@section('conteudo')
<div class="sf-cabecalho">
    <div class="d-flex align-items-center gap-3">
        <span class="sf-avatar sf-avatar-g" aria-hidden="true">{{ $usuario->iniciais }}</span>
        <div>
            <h1>{{ $usuario->name }}</h1>
            <p class="m-0">{{ $usuario->papel_nome }}@if ($usuario->ra), RA {{ $usuario->ra }}@endif</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        @include('partials.ficha-pessoa', ['pessoa' => $usuario])
        @unless ($usuario->isCoordenacao())
            <p class="text-secondary small mt-2">Algum dado errado? Peça para a secretaria corrigir.</p>
        @endunless
    </div>
    <div class="col-lg-6">
        <form method="POST" action="{{ route('perfil.senha') }}" class="sf-folha" novalidate>
            @csrf
            @method('PUT')
            <div class="sf-folha-cab"><h2>Trocar senha</h2></div>
            <div class="sf-folha-corpo">
                <x-campo nome="senha_atual" rotulo="Senha atual" tipo="password" autocomplete="current-password" obrigatorio />
                <x-campo nome="password" rotulo="Nova senha" tipo="password" autocomplete="new-password" ajuda="Pelo menos 8 caracteres." obrigatorio />
                <x-campo nome="password_confirmation" rotulo="Repita a nova senha" tipo="password" autocomplete="new-password" obrigatorio class="mb-0" />
            </div>
            <div class="sf-rodape-form"><button type="submit" class="btn btn-primary">Trocar senha</button></div>
        </form>
    </div>
</div>
@endsection
