<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition()
    {
        $nome = fake()->firstName().' '.fake()->lastName().(fake()->boolean(40) ? ' '.fake()->lastName() : '');

        return [
            'name' => $nome,
            'email' => fake()->unique()->numerify(Str::slug(Str::words($nome, 2, ''), '.').'##').'@email.test',
            'papel' => User::ALUNO,
            'cpf' => self::cpfValido(),
            'telefone' => fake()->numerify('(##) 9####-####'),
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
        ];
    }

    public function coordenacao()
    {
        return $this->state(fn () => ['papel' => User::COORDENACAO]);
    }

    public function professor()
    {
        return $this->state(fn () => ['papel' => User::PROFESSOR]);
    }

    public function aluno()
    {
        return $this->state(fn () => [
            'papel' => User::ALUNO,
            'data_nascimento' => fake()->dateTimeBetween('-40 years', '-16 years')->format('Y-m-d'),
            'filme_favorito' => fake()->randomElement([
                'O Auto da Compadecida', 'Cidade de Deus', 'Central do Brasil', 'Interestelar',
                'De Volta para o Futuro', 'A Viagem de Chihiro', 'Matrix', 'Bacurau',
            ]),
            'cep' => '13083970',
            'rua' => 'Rua Sérgio Buarque de Holanda',
            'numero' => (string) fake()->numberBetween(1, 999),
            'bairro' => 'Cidade Universitária',
            'cidade' => 'Campinas',
            'uf' => 'SP',
        ]);
    }

    /** Gera um CPF com digitos verificadores validos. */
    public static function cpfValido(): string
    {
        do {
            $n = [];
            for ($i = 0; $i < 9; $i++) {
                $n[] = random_int(0, 9);
            }
        } while (count(array_unique($n)) === 1);

        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($i = 0; $i < $t; $i++) {
                $soma += $n[$i] * (($t + 1) - $i);
            }
            $n[] = ((10 * $soma) % 11) % 10;
        }

        return implode('', $n);
    }
}
