{{-- Campos em comum de aluno e professor: identificacao e acesso. $pessoa, $editando --}}
<section class="sf-secao">
    <header><h2>Dados pessoais</h2><p>Nome, CPF e contato.</p></header>
    <div>
        <x-campo nome="name" rotulo="Nome completo" :valor="$pessoa->name" autocomplete="off" maxlength="120" obrigatorio />
        <div class="row">
            <x-campo class="col-md-6" nome="cpf" rotulo="CPF" :valor="$pessoa->cpf_formatado" data-mascara="cpf" inputmode="numeric" placeholder="000.000.000-00" obrigatorio />
            <x-campo class="col-md-6" nome="telefone" rotulo="Telefone" :valor="$pessoa->telefone" data-mascara="telefone" inputmode="tel" placeholder="(00) 00000-0000" />
        </div>
        @if (! empty($extraAluno))
            @include('partials.campos-aluno-extra', ['aluno' => $pessoa])
        @endif
    </div>
</section>

<section class="sf-secao">
    <header>
        <h2>Acesso ao portal</h2>
        <p>{{ $editando ? 'Deixe a senha em branco para manter a atual.' : 'Combine a senha inicial com a pessoa; ela pode trocar depois no perfil.' }}</p>
    </header>
    <div>
        <x-campo nome="email" rotulo="E-mail" tipo="email" :valor="$pessoa->email" autocomplete="off" obrigatorio />
        <div class="row">
            <x-campo class="col-md-6" nome="password" rotulo="{{ $editando ? 'Nova senha' : 'Senha inicial' }}" tipo="password" autocomplete="new-password" ajuda="Pelo menos 8 caracteres." :obrigatorio="! $editando" />
            <x-campo class="col-md-6" nome="password_confirmation" rotulo="Repita a senha" tipo="password" autocomplete="new-password" :obrigatorio="! $editando" />
        </div>
    </div>
</section>
