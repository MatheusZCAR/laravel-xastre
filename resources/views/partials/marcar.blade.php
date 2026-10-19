{{-- Lista de caixas de marcar com filtro.
     Parametros: $nome (ex.: "alunos"), $itens (colecao), $marcados (ids), $rotulo, $descricao (closure item => texto extra), $vazio --}}
<div class="sf-marcar" data-marcar>
    @if ($itens->isEmpty())
        <p class="m-0 p-3 text-secondary">{{ $vazio }}</p>
    @else
        <div class="sf-marcar-filtro">
            <label class="visually-hidden" for="filtro-{{ $nome }}">Filtrar {{ $rotulo }}</label>
            <input type="search" class="form-control form-control-sm" id="filtro-{{ $nome }}" placeholder="Filtrar {{ $rotulo }}" data-marcar-filtro>
        </div>
        <div class="sf-marcar-lista">
            @foreach ($itens as $item)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="{{ $nome }}[]" value="{{ $item->id }}" id="{{ $nome }}-{{ $item->id }}" @checked(in_array($item->id, array_map('intval', (array) $marcados), true))>
                    <label class="form-check-label d-flex justify-content-between gap-2" for="{{ $nome }}-{{ $item->id }}">
                        <span>{{ $item->name ?? $item->nome }}</span>
                        <span class="text-secondary small">{{ $descricao($item) }}</span>
                    </label>
                </div>
            @endforeach
            <p class="m-0 p-2 text-secondary small" data-marcar-vazio hidden>Nada encontrado com esse filtro.</p>
        </div>
        <div class="sf-marcar-rodape" data-marcar-contador aria-live="polite"></div>
    @endif
</div>
@error($nome)<div class="text-danger small mt-1">{{ $message }}</div>@enderror
@error($nome.'.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
