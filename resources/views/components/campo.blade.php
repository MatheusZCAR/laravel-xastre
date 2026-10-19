{{-- Campo de formulario com rotulo, valor antigo e mensagem de erro.
     Uso: <x-campo nome="email" rotulo="E-mail" tipo="email" :valor="$aluno->email" /> --}}
@props(['nome', 'rotulo', 'tipo' => 'text', 'valor' => null, 'ajuda' => null, 'obrigatorio' => false])
@php $id = $attributes->get('id', 'campo-'.$nome); @endphp
<div {{ $attributes->only('class')->merge(['class' => 'mb-3']) }}>
    <label class="form-label" for="{{ $id }}">{{ $rotulo }}@if ($obrigatorio)<span class="text-danger" aria-hidden="true"> *</span>@endif</label>
    <input
        type="{{ $tipo }}"
        id="{{ $id }}"
        name="{{ $nome }}"
        value="{{ $tipo === 'password' ? '' : old($nome, $valor) }}"
        {{ $attributes->except(['class', 'id'])->merge(['class' => 'form-control'.($errors->has($nome) ? ' is-invalid' : '')]) }}
        @if ($obrigatorio) required @endif
        @if ($ajuda) aria-describedby="{{ $id }}-ajuda" @endif
    >
    @error($nome)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if ($ajuda)
        <div class="form-text" id="{{ $id }}-ajuda">{{ $ajuda }}</div>
    @endif
</div>
