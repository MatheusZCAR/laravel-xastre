@props(['icone' => 'inbox'])
<div class="sf-vazio">
    <i class="bi bi-{{ $icone }}" aria-hidden="true"></i>
    {{ $slot }}
</div>
