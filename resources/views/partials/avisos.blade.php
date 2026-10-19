@if (session('ok'))
    <div class="sf-aviso sf-aviso-ok" role="status"><i class="bi bi-check-circle-fill" aria-hidden="true"></i> {{ session('ok') }}</div>
@endif
@if ($errors->any())
    <div class="sf-aviso sf-aviso-erro" role="alert">
        <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
        {{ $errors->count() === 1 ? 'Corrija o campo destacado abaixo.' : 'Corrija os '.$errors->count().' campos destacados abaixo.' }}
    </div>
@endif
