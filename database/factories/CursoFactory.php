<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Curso>
 */
class CursoFactory extends Factory
{
    public function definition()
    {
        return [
            'nome' => 'Curso de '.fake()->unique()->words(2, true),
            'descricao' => fake()->sentence(14),
            'conteudo' => fake()->paragraph(),
            'carga_horaria' => fake()->randomElement([20, 40, 60, 80]),
            'professor_id' => null,
        ];
    }
}
