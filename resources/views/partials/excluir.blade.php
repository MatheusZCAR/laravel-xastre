{{-- Botao de excluir com confirmacao. Parametros: $acao (url), $pergunta, $rotulo --}}
<form method="POST" action="{{ $acao }}" data-confirmar="{{ $pergunta }}" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-perigo"><i class="bi bi-trash3 me-1" aria-hidden="true"></i>{{ $rotulo }}</button>
</form>
