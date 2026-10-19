@extends('layouts.painel')

@php $editando = $professor->exists; @endphp

@section('titulo', $editando ? 'Editar professor' : 'Cadastrar professor')

@section('conteudo')
<a class="sf-voltar" href="{{ $editando ? route('coordenacao.professores.show', $professor) : route('coordenacao.professores.index') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i>{{ $editando ? $professor->name : 'Professores' }}</a>
<div class="sf-cabecalho"><h1>{{ $editando ? 'Editar professor' : 'Cadastrar professor' }}</h1></div>

<form method="POST" action="{{ $editando ? route('coordenacao.professores.update', $professor) : route('coordenacao.professores.store') }}" class="sf-folha" novalidate>
    @csrf
    @if ($editando) @method('PUT') @endif

    @include('partials.campos-pessoa', ['pessoa' => $professor, 'editando' => $editando])

    <div class="sf-rodape-form">
        <a class="btn btn-contorno" href="{{ $editando ? route('coordenacao.professores.show', $professor) : route('coordenacao.professores.index') }}">Cancelar</a>
        <button type="submit" class="btn btn-primary">{{ $editando ? 'Salvar alterações' : 'Cadastrar professor' }}</button>
    </div>
</form>
@endsection
