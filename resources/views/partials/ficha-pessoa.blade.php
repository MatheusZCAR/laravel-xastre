<section class="sf-folha">
    <div class="sf-folha-cab"><h2>Cadastro</h2></div>
    <div class="sf-folha-corpo">
        <dl class="sf-ficha">
            <dt>E-mail</dt><dd><a href="mailto:{{ $pessoa->email }}">{{ $pessoa->email }}</a></dd>
            <dt>CPF</dt><dd>{{ $pessoa->cpf_formatado ?? '—' }}</dd>
            <dt>Telefone</dt><dd>{{ $pessoa->telefone ?: '—' }}</dd>
            @if ($pessoa->isAluno())
                <dt>Nascimento</dt><dd>{{ $pessoa->data_nascimento ? $pessoa->data_nascimento->format('d/m/Y') : '—' }}</dd>
                <dt>Endereço</dt>
                <dd>
                    @if ($pessoa->rua)
                        {{ $pessoa->rua }}{{ $pessoa->numero ? ', '.$pessoa->numero : '' }}<br>
                        {{ $pessoa->bairro }}{{ $pessoa->bairro ? ', ' : '' }}{{ $pessoa->cidade }}{{ $pessoa->uf ? '/'.$pessoa->uf : '' }}<br>
                        CEP {{ $pessoa->cep_formatado }}
                    @else
                        —
                    @endif
                </dd>
                <dt>Filme favorito</dt><dd>{{ $pessoa->filme_favorito ?: '—' }}</dd>
            @endif
            <dt>Cadastrado em</dt><dd>{{ $pessoa->created_at->format('d/m/Y') }}</dd>
        </dl>
    </div>
</section>
