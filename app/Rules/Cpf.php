<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Valida um CPF conferindo os dois digitos verificadores.
 * Aceita com ou sem pontuacao (123.456.789-09 ou 12345678909).
 */
class Cpf implements Rule
{
    public function passes($attribute, $value)
    {
        $cpf = preg_replace('/\D/', '', (string) $value);

        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($i = 0; $i < $t; $i++) {
                $soma += (int) $cpf[$i] * (($t + 1) - $i);
            }
            $digito = ((10 * $soma) % 11) % 10;
            if ((int) $cpf[$t] !== $digito) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'O CPF informado não é válido.';
    }
}
