<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CursoRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()?->isCoordenacao() ?? false;
    }

    public function rules()
    {
        $curso = $this->route('curso');

        return [
            'nome' => ['required', 'string', 'max:120', Rule::unique('cursos', 'nome')->ignore($curso)],
            'descricao' => ['required', 'string', 'max:500'],
            'conteudo' => ['nullable', 'string', 'max:5000'],
            'carga_horaria' => ['required', 'integer', 'min:1', 'max:2000'],
            'professor_id' => ['nullable', Rule::exists('users', 'id')->where('papel', User::PROFESSOR)],
            'alunos' => ['nullable', 'array'],
            'alunos.*' => [Rule::exists('users', 'id')->where('papel', User::ALUNO)],
        ];
    }

    public function messages()
    {
        return [
            'nome.unique' => 'Já existe um curso com este nome.',
            'alunos.*.exists' => 'Um dos alunos escolhidos não existe mais. Recarregue a página.',
        ];
    }
}
