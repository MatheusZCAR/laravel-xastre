<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoordenacaoCursosTest extends TestCase
{
    use RefreshDatabase;

    private User $coordenacao;

    protected function setUp(): void
    {
        parent::setUp();
        $this->coordenacao = User::factory()->coordenacao()->create();
        $this->actingAs($this->coordenacao);
    }

    public function test_lista_e_busca_cursos()
    {
        Curso::factory()->create(['nome' => 'Lógica de Programação']);
        Curso::factory()->create(['nome' => 'Redes de Computadores']);

        $this->get('/coordenacao/cursos')->assertOk()->assertSee('Lógica de Programação')->assertSee('Redes de Computadores');
        $this->get('/coordenacao/cursos?busca=redes')->assertOk()->assertSee('Redes de Computadores')->assertDontSee('Lógica de Programação');
    }

    public function test_cria_curso_com_professor_e_alunos()
    {
        $professor = User::factory()->professor()->create();
        $alunos = User::factory()->aluno()->count(3)->create();

        $resposta = $this->post('/coordenacao/cursos', [
            'nome' => 'Banco de Dados',
            'descricao' => 'SQL do básico ao avançado.',
            'conteudo' => "SELECT\nJOIN",
            'carga_horaria' => 60,
            'professor_id' => $professor->id,
            'alunos' => $alunos->pluck('id')->all(),
        ]);

        $curso = Curso::firstWhere('nome', 'Banco de Dados');
        $resposta->assertRedirect("/coordenacao/cursos/{$curso->id}")->assertSessionHas('ok', 'Curso criado.');
        $this->assertSame($professor->id, $curso->professor_id);
        $this->assertCount(3, $curso->alunos);

        $this->get("/coordenacao/cursos/{$curso->id}")->assertOk()->assertSee($alunos->first()->name);
    }

    public function test_validacao_do_curso()
    {
        Curso::factory()->create(['nome' => 'Repetido']);
        $aluno = User::factory()->aluno()->create();

        $this->post('/coordenacao/cursos', [
            'nome' => 'Repetido',
            'descricao' => '',
            'carga_horaria' => 0,
            'professor_id' => $aluno->id, // um aluno nao pode ser professor do curso
        ])->assertSessionHasErrors([
            'nome' => 'Já existe um curso com este nome.',
            'descricao' => 'Preencha o campo descrição.',
            'carga_horaria',
            'professor_id',
        ]);

        $this->assertDatabaseCount('cursos', 1);
    }

    public function test_edita_curso_e_troca_matriculas()
    {
        $curso = Curso::factory()->create();
        [$a, $b] = User::factory()->aluno()->count(2)->create();
        $curso->alunos()->attach($a);

        $this->put("/coordenacao/cursos/{$curso->id}", [
            'nome' => 'Nome Novo',
            'descricao' => 'Descrição nova.',
            'carga_horaria' => 40,
            'alunos' => [$b->id],
        ])->assertRedirect("/coordenacao/cursos/{$curso->id}");

        $curso->refresh();
        $this->assertSame('Nome Novo', $curso->nome);
        $this->assertNull($curso->professor_id);
        $this->assertSame([$b->id], $curso->alunos->pluck('id')->all());
    }

    public function test_editar_sem_mudar_o_nome_nao_acusa_nome_repetido()
    {
        $curso = Curso::factory()->create(['nome' => 'Mesmo Nome']);

        $this->put("/coordenacao/cursos/{$curso->id}", [
            'nome' => 'Mesmo Nome', 'descricao' => 'Outra descrição.', 'carga_horaria' => 20,
        ])->assertSessionHasNoErrors();
    }

    public function test_exclui_curso_e_as_matriculas()
    {
        $curso = Curso::factory()->create();
        $aluno = User::factory()->aluno()->create();
        $curso->alunos()->attach($aluno);

        $this->delete("/coordenacao/cursos/{$curso->id}")->assertRedirect('/coordenacao/cursos');

        $this->assertModelMissing($curso);
        $this->assertModelExists($aluno);
        $this->assertDatabaseCount('curso_aluno', 0);
    }

    public function test_formularios_abrem()
    {
        $curso = Curso::factory()->create();
        $this->get('/coordenacao')->assertOk();
        $this->get('/coordenacao/cursos/create')->assertOk()->assertSee('Novo curso');
        $this->get("/coordenacao/cursos/{$curso->id}/edit")->assertOk()->assertSee($curso->nome);
    }
}
