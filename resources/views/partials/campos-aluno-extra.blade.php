<div class="row">
    <x-campo class="col-md-6" nome="data_nascimento" rotulo="Data de nascimento" tipo="date" :valor="optional($aluno->data_nascimento)->format('Y-m-d')" max="{{ now()->subDay()->format('Y-m-d') }}" />
    <x-campo class="col-md-6" nome="filme_favorito" rotulo="Filme favorito" :valor="$aluno->filme_favorito" maxlength="120" ajuda="Opcional. Usado nas atividades de boas-vindas." />
</div>
