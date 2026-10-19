<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutenticacaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_pagina_inicial_e_login_abrem()
    {
        $this->get('/')->assertOk()->assertSee('Entrar no portal');
        $this->get('/login')->assertOk()->assertSee('Entrar no portal');
    }

    /** @dataProvider papeis */
    public function test_cada_papel_vai_para_o_seu_painel(string $estado, string $painel)
    {
        $usuario = User::factory()->{$estado}()->create(['password' => 'segredo123']);

        $this->post('/login', ['email' => $usuario->email, 'password' => 'segredo123'])
            ->assertRedirect($painel);

        $this->assertAuthenticatedAs($usuario);
        $this->get($painel)->assertOk();
    }

    public function papeis(): array
    {
        return [
            'coordenacao' => ['coordenacao', '/coordenacao'],
            'professor' => ['professor', '/professor'],
            'aluno' => ['aluno', '/aluno'],
        ];
    }

    public function test_senha_errada_mostra_mensagem_em_portugues()
    {
        $usuario = User::factory()->aluno()->create(['password' => 'segredo123']);

        $this->from('/login')->post('/login', ['email' => $usuario->email, 'password' => 'errada'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email' => 'E-mail ou senha incorretos.']);

        $this->assertGuest();
    }

    public function test_login_e_bloqueado_depois_de_5_tentativas()
    {
        $usuario = User::factory()->aluno()->create(['password' => 'segredo123']);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => $usuario->email, 'password' => 'errada']);
        }

        $this->post('/login', ['email' => $usuario->email, 'password' => 'segredo123'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_senha_e_gravada_com_hash()
    {
        $usuario = User::factory()->aluno()->create(['password' => 'segredo123']);

        $this->assertNotSame('segredo123', $usuario->getAttributes()['password']);
        $this->assertTrue(password_verify('segredo123', $usuario->getAttributes()['password']));
    }

    public function test_sair_encerra_a_sessao()
    {
        $this->actingAs(User::factory()->aluno()->create())
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_quem_esta_logado_e_levado_do_login_para_o_painel()
    {
        $this->actingAs(User::factory()->professor()->create())
            ->get('/login')
            ->assertRedirect('/professor');
    }

    public function test_nao_existe_cadastro_publico()
    {
        // antes qualquer visitante se cadastrava (e podia escolher ser administrador)
        $this->get('/register')->assertNotFound();
        $this->post('/register', [
            'name' => 'Invasor', 'email' => 'x@x.com', 'role' => 1,
            'password' => 'segredo123', 'password_confirmation' => 'segredo123',
        ])->assertStatus(404);
        $this->assertDatabaseMissing('users', ['email' => 'x@x.com']);
    }
}
