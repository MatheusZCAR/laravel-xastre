<?php

namespace App\Http\Requests;

use App\Models\Curso;
use Illuminate\Validation\Rule;

class AlunoRequest extends PessoaRequest
{
    protected function parametroRota(): string
    {
        return 'aluno';
    }

    public function rules()
    {
        return parent::rules() + [
            'data_nascimento' => ['nullable', 'date', 'before:today', 'after:1900-01-01'],
            'filme_favorito' => ['nullable', 'string', 'max:120'],
            'cep' => ['nullable', 'digits:8'],
            'rua' => ['nullable', 'string', 'max:150'],
            'numero' => ['nullable', 'string', 'max:20'],
            'bairro' => ['nullable', 'string', 'max:100'],
            'cidade' => ['nullable', 'string', 'max:100'],
            'uf' => ['nullable', Rule::in(self::UFS)],
            'cursos' => ['nullable', 'array'],
            'cursos.*' => [Rule::exists((new Curso())->getTable(), 'id')],
        ];
    }

    public function messages()
    {
        return parent::messages() + [
            'cep.digits' => 'O CEP deve ter 8 números.',
            'data_nascimento.before' => 'A data de nascimento precisa ser no passado.',
            'uf.in' => 'Escolha um estado da lista.',
        ];
    }

    public const UFS = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB',
        'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
}
