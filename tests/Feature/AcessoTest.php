<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcessoTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitante_e_mandado_para_o_login()
    {
        foreach (['/painel', '/perfil', '/coordenacao', '/coordenacao/cursos', '/coordenacao/alunos', '/professor', '/aluno'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
    }

    public function test_visitante_nao_cria_curso()
    {
        // antes /criar_novo_curso aceitava POST de qualquer pessoa
        $this->post('/coordenacao/cursos', ['nome' => 'Curso pirata'])->assertRedirect('/login');
        $this->post('/criar_novo_curso', ['nome' => 'Curso pirata'])->assertNotFound();
        $this->assertDatabaseCount('cursos', 0);
    }

    public function test_aluno_e_professor_nao_entram_na_coordenacao()
    {
        foreach ([User::factory()->aluno()->create(), User::factory()->professor()->create()] as $usuario) {
            $this->actingAs($usuario)->get('/coordenacao')->assertForbidden();
            $this->actingAs($usuario)->get('/coordenacao/alunos')->assertForbidden();
            $this->actingAs($usuario)->post('/coordenacao/cursos', ['nome' => 'X'])->assertForbidden();
        }
        $this->assertDatabaseCount('cursos', 0);
    }

    public function test_aluno_nao_entra_no_painel_do_professor_e_vice_versa()
    {
        $this->actingAs(User::factory()->aluno()->create())->get('/professor')->assertForbidden();
        $this->actingAs(User::factory()->professor()->create())->get('/aluno')->assertForbidden();
    }

    public function test_dados_de_um_usuario_nao_ficam_publicos()
    {
        // antes /user/{id} mostrava CPF e endereco de qualquer pessoa, sem login
        $aluno = User::factory()->aluno()->create();
        $this->get("/user/{$aluno->id}")->assertNotFound();
        $this->get('/area_do_aluno/1')->assertNotFound();
    }

    public function test_professor_so_ve_a_turma_dos_proprios_cursos()
    {
        $professor = User::factory()->professor()->create();
        $outro = User::factory()->professor()->create();
        $meu = Curso::factory()->create(['professor_id' => $professor->id]);
        $alheio = Curso::factory()->create(['professor_id' => $outro->id]);
        $aluno = User::factory()->aluno()->create(['name' => 'Aluna da Turma']);
        $meu->alunos()->attach($aluno);

        $this->actingAs($professor)->get("/professor/cursos/{$meu->id}")->assertOk()->assertSee('Aluna da Turma');
        $this->actingAs($professor)->get("/professor/cursos/{$alheio->id}")->assertForbidden();
    }

    public function test_aluno_ve_so_os_proprios_cursos()
    {
        $aluno = User::factory()->aluno()->create();
        $meu = Curso::factory()->create(['nome' => 'Curso Que Faço']);
        Curso::factory()->create(['nome' => 'Curso Que Não Faço']);
        $aluno->cursosMatriculados()->attach($meu);

        $this->actingAs($aluno)->get('/aluno')
            ->assertOk()
            ->assertSee('Curso Que Faço')
            ->assertDontSee('Curso Que Não Faço');
    }

    public function test_rota_de_aluno_nao_abre_cadastro_de_professor()
    {
        $coordenacao = User::factory()->coordenacao()->create();
        $professor = User::factory()->professor()->create();

        $this->actingAs($coordenacao)->get("/coordenacao/alunos/{$professor->id}")->assertNotFound();
        $this->actingAs($coordenacao)->delete("/coordenacao/alunos/{$professor->id}")->assertNotFound();
        $this->assertModelExists($professor);
    }

    public function test_paginas_do_painel_mandam_o_navegador_nao_guardar_cache()
    {
        $resposta = $this->actingAs(User::factory()->aluno()->create())->get('/aluno');
        $this->assertStringContainsString('no-store', $resposta->headers->get('Cache-Control'));
    }
}
