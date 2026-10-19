<?php

namespace App\Http\Requests;

use App\Rules\Cpf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Regras em comum para cadastrar alunos e professores.
 */
abstract class PessoaRequest extends FormRequest
{
    /** Nome do parametro da rota que traz a pessoa sendo editada. */
    abstract protected function parametroRota(): string;

    public function authorize()
    {
        return $this->user()?->isCoordenacao() ?? false;
    }

    /** CPF, CEP e telefone chegam com mascara; guardamos so os numeros. */
    protected function prepareForValidation()
    {
        $limpo = [];
        foreach (['cpf', 'cep'] as $campo) {
            if ($this->filled($campo)) {
                $limpo[$campo] = preg_replace('/\D/', '', $this->input($campo));
            }
        }
        if ($this->filled('uf')) {
            $limpo['uf'] = strtoupper(trim($this->input('uf')));
        }
        if ($this->filled('email')) {
            $limpo['email'] = strtolower(trim($this->input('email')));
        }
        $this->merge($limpo);
    }

    public function rules()
    {
        $pessoa = $this->route($this->parametroRota());
        $editando = $pessoa !== null;

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($pessoa)],
            'cpf' => ['required', new Cpf(), Rule::unique('users', 'cpf')->ignore($pessoa)],
            'telefone' => ['nullable', 'string', 'max:20'],
            'password' => [$editando ? 'nullable' : 'required', 'confirmed', Password::min(8)],
        ];
    }

    public function messages()
    {
        return [
            'cpf.unique' => 'Já existe uma pessoa cadastrada com este CPF.',
            'email.unique' => 'Já existe uma pessoa cadastrada com este e-mail.',
        ];
    }

    /** Dados prontos para gravar (sem senha vazia na edicao). */
    public function dados(): array
    {
        $dados = collect($this->validated())->except(['cursos', 'password_confirmation'])->all();
        if (empty($dados['password'])) {
            unset($dados['password']);
        }

        return $dados;
    }
}
