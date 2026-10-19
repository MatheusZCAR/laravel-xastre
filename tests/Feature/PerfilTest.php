<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PerfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_perfil_mostra_os_dados_de_quem_esta_logado()
    {
        $aluno = User::factory()->aluno()->create(['name' => 'Joana Perfil']);

        $this->actingAs($aluno)->get('/perfil')->assertOk()->assertSee('Joana Perfil')->assertSee($aluno->ra);
    }

    public function test_troca_a_senha()
    {
        $usuario = User::factory()->professor()->create(['password' => 'antiga1234']);

        $this->actingAs($usuario)->from('/perfil')->put('/perfil/senha', [
            'senha_atual' => 'antiga1234',
            'password' => 'novasenha99',
            'password_confirmation' => 'novasenha99',
        ])->assertRedirect('/perfil')->assertSessionHas('ok', 'Senha alterada.');

        $this->assertTrue(Hash::check('novasenha99', $usuario->fresh()->password));
    }

    public function test_exige_a_senha_atual_correta()
    {
        $usuario = User::factory()->aluno()->create(['password' => 'antiga1234']);

        $this->actingAs($usuario)->from('/perfil')->put('/perfil/senha', [
            'senha_atual' => 'chute',
            'password' => 'novasenha99',
            'password_confirmation' => 'novasenha99',
        ])->assertSessionHasErrors(['senha_atual' => 'A senha atual está incorreta.']);

        $this->assertTrue(Hash::check('antiga1234', $usuario->fresh()->password));
    }
}
