@extends('layouts.base')

@section('titulo', 'Entrar')

@section('corpo')
<div class="sf-login">
    <section class="sf-ceu sf-login-ceu">
        @include('partials.marca')
        <div class="sf-heroi-arte my-4">@include('partials.constelacao')</div>
        <blockquote>Cursos, turmas e matrículas da escola em um só lugar.</blockquote>
    </section>

    <main class="sf-login-form">
        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf
            <h1>Entrar no portal</h1>
            <p class="text-secondary mb-4">Use o e-mail e a senha que a secretaria cadastrou para você.</p>

            @if (session('ok'))
                <div class="sf-aviso sf-aviso-ok" role="status">{{ session('ok') }}</div>
            @endif

            <x-campo nome="email" rotulo="E-mail" tipo="email" autocomplete="email" autofocus obrigatorio />
            <x-campo nome="password" rotulo="Senha" tipo="password" autocomplete="current-password" obrigatorio />

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" @checked(old('remember'))>
                <label class="form-check-label" for="remember">Continuar conectado neste computador</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">Entrar</button>
            <p class="small text-secondary mt-3 mb-0">Esqueceu a senha? Peça para a secretaria redefinir.</p>

            @if (app()->environment('local'))
                <div class="sf-demo">
                    <strong class="d-block mb-1 text-body">Contas de demonstração</strong>
                    Senha de todas: <code>starfox123</code><br>
                    <code>coordenacao@starfox.test</code><br>
                    <code>professora@starfox.test</code><br>
                    <code>aluno@starfox.test</code>
                </div>
            @endif

            <p class="mt-4 mb-0"><a href="{{ route('home') }}" class="sf-voltar"><i class="bi bi-arrow-left" aria-hidden="true"></i>Voltar para o início</a></p>
        </form>
    </main>
</div>
@endsection
