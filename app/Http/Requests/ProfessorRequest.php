<?php

namespace App\Http\Requests;

class ProfessorRequest extends PessoaRequest
{
    protected function parametroRota(): string
    {
        return 'professor';
    }
}
