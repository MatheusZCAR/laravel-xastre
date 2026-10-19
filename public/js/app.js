/* Star-Fox Company - comportamentos das telas (sem dependencias alem do Bootstrap) */
(function () {
    'use strict';

    var somenteNumeros = function (v) { return (v || '').replace(/\D/g, ''); };

    // ---- mascaras: CPF 000.000.000-00, CEP 00000-000, telefone (00) 00000-0000
    var mascaras = {
        cpf: function (v) {
            v = somenteNumeros(v).slice(0, 11);
            return v.replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        },
        cep: function (v) {
            v = somenteNumeros(v).slice(0, 8);
            return v.replace(/(\d{5})(\d)/, '$1-$2');
        },
        telefone: function (v) {
            v = somenteNumeros(v).slice(0, 11);
            if (v.length <= 10) return v.replace(/(\d{2})(\d)/, '($1) $2').replace(/(\d{4})(\d)/, '$1-$2');
            return v.replace(/(\d{2})(\d)/, '($1) $2').replace(/(\d{5})(\d)/, '$1-$2');
        }
    };
    document.querySelectorAll('[data-mascara]').forEach(function (campo) {
        var aplicar = mascaras[campo.dataset.mascara];
        if (!aplicar) return;
        campo.value = aplicar(campo.value);
        campo.addEventListener('input', function () { campo.value = aplicar(campo.value); });
    });

    // ---- CEP: preenche rua, bairro, cidade e estado pela API ViaCEP
    document.querySelectorAll('[data-cep]').forEach(function (campo) {
        var form = campo.form;
        var aviso = document.getElementById(campo.dataset.cep);
        var preencher = function (dados) {
            ['rua', 'bairro', 'cidade', 'uf'].forEach(function (nome) {
                var alvo = form.elements[nome];
                if (alvo && dados[nome] !== undefined) alvo.value = dados[nome];
            });
        };
        var ultimo = '';
        var buscar = function () {
            var cep = somenteNumeros(campo.value);
            if (cep.length !== 8 || cep === ultimo) return;
            ultimo = cep;
            aviso.textContent = 'Buscando endereço...';
            fetch('https://viacep.com.br/ws/' + cep + '/json/')
                .then(function (r) { if (!r.ok) throw new Error(); return r.json(); })
                .then(function (d) {
                    if (d.erro) { aviso.textContent = 'CEP não encontrado. Preencha o endereço manualmente.'; return; }
                    preencher({ rua: d.logradouro, bairro: d.bairro, cidade: d.localidade, uf: d.uf });
                    aviso.textContent = 'Endereço preenchido pelo CEP. Confira e complete o número.';
                    if (form.elements.numero) form.elements.numero.focus();
                })
                .catch(function () { aviso.textContent = 'Não foi possível consultar o CEP agora. Preencha o endereço manualmente.'; });
        };
        campo.addEventListener('input', buscar);
        campo.addEventListener('blur', buscar);
    });

    // ---- confirmar antes de excluir
    document.querySelectorAll('form[data-confirmar]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!window.confirm(form.dataset.confirmar)) e.preventDefault();
        });
    });

    // ---- listas de marcar (alunos de um curso / cursos de um aluno): filtro e contagem
    document.querySelectorAll('[data-marcar]').forEach(function (caixa) {
        var filtro = caixa.querySelector('[data-marcar-filtro]');
        var itens = Array.prototype.slice.call(caixa.querySelectorAll('.form-check'));
        var contador = caixa.querySelector('[data-marcar-contador]');
        var semResultado = caixa.querySelector('[data-marcar-vazio]');
        var normalizar = function (t) { return (t || '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase(); };
        var contar = function () {
            var n = caixa.querySelectorAll('input[type=checkbox]:checked').length;
            if (contador) contador.textContent = n === 1 ? '1 selecionado' : n + ' selecionados';
        };
        if (filtro) {
            filtro.addEventListener('input', function () {
                var termo = normalizar(filtro.value);
                var visiveis = 0;
                itens.forEach(function (item) {
                    var ok = normalizar(item.textContent).indexOf(termo) !== -1;
                    item.hidden = !ok;
                    if (ok) visiveis++;
                });
                if (semResultado) semResultado.hidden = visiveis !== 0;
            });
        }
        caixa.addEventListener('change', contar);
        contar();
    });
})();
