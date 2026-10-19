<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CoordenacaoPessoasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->coordenacao()->create());
    }

    private function dadosAluno(array $extra = []): array
    {
        return array_merge([
            'name' => 'Ana Clara Souza',
            'email' => 'Ana.Clara@Email.test',
            'cpf' => '529.982.247-25',
            'telefone' => '(19) 99999-1234',
            'data_nascimento' => '2004-05-10',
            'filme_favorito' => 'Central do Brasil',
            'cep' => '13083-970',
            'rua' => 'Rua Sérgio Buarque de Holanda',
            'numero' => '251',
            'bairro' => 'Cidade Universitária',
            'cidade' => 'Campinas',
            'uf' => 'sp',
            'password' => 'senhaforte1',
            'password_confirmation' => 'senhaforte1',
        ], $extra);
    }

    public function test_cadastra_aluno_com_ra_senha_com_hash_e_cursos()
    {
        $curso = Curso::factory()->create();

        $resposta = $this->post('/coordenacao/alunos', $this->dadosAluno(['cursos' => [$curso->id]]));

        $aluno = User::firstWhere('email', 'ana.clara@email.test');
        $resposta->assertRedirect("/coordenacao/alunos/{$aluno->id}");
        $this->assertSame(User::ALUNO, $aluno->papel);
        $this->assertSame('52998224725', $aluno->cpf);   // gravado so com numeros
        $this->assertSame('13083970', $aluno->cep);
        $this->assertSame('SP', $aluno->uf);
        $this->assertMatchesRegularExpression('/^\d{4}\d{5}$/', $aluno->ra);
        $this->assertTrue(Hash::check('senhaforte1', $aluno->password));
        $this->assertSame([$curso->id], $aluno->cursosMatriculados->pluck('id')->all());

        $this->get("/coordenacao/alunos/{$aluno->id}")->assertOk()->assertSee('529.982.247-25')->assertSee('13083-970');
    }

    public function test_papel_nao_pode_ser_escolhido_pelo_formulario()
    {
        // antes o cadastro gravava o "role" que viesse do formulario
        $this->post('/coordenacao/alunos', $this->dadosAluno(['papel' => 'coordenacao', 'role' => 1, 'ra' => '1']));

        $aluno = User::firstWhere('email', 'ana.clara@email.test');
        $this->assertSame(User::ALUNO, $aluno->papel);
        $this->assertNotSame('1', $aluno->ra);
    }

    public function test_recusa_cpf_invalido_ou_repetido_e_email_repetido()
    {
        User::factory()->aluno()->create(['cpf' => '52998224725', 'email' => 'ana.clara@email.test']);

        $this->post('/coordenacao/alunos', $this->dadosAluno())
            ->assertSessionHasErrors([
                'cpf' => 'Já existe uma pessoa cadastrada com este CPF.',
                'email' => 'Já existe uma pessoa cadastrada com este e-mail.',
            ]);

        $this->post('/coordenacao/alunos', $this->dadosAluno(['cpf' => '123.456.789-00', 'email' => 'outra@email.test']))
            ->assertSessionHasErrors(['cpf' => 'O CPF informado não é válido.']);
    }

    public function test_recusa_senha_curta_ou_diferente_e_cep_incompleto()
    {
        $this->post('/coordenacao/alunos', $this->dadosAluno(['password' => 'curta', 'password_confirmation' => 'outra', 'cep' => '1308']))
            ->assertSessionHasErrors(['password', 'cep' => 'O CEP deve ter 8 números.']);
        $this->assertDatabaseMissing('users', ['email' => 'ana.clara@email.test']);
    }

    public function test_editar_sem_senha_mantem_a_senha_atual()
    {
        $aluno = User::factory()->aluno()->create(['password' => 'antiga1234']);

        $this->put("/coordenacao/alunos/{$aluno->id}", [
            'name' => 'Nome Corrigido',
            'email' => $aluno->email,
            'cpf' => $aluno->cpf,
            'password' => '',
            'password_confirmation' => '',
        ])->assertRedirect("/coordenacao/alunos/{$aluno->id}")->assertSessionHasNoErrors();

        $aluno->refresh();
        $this->assertSame('Nome Corrigido', $aluno->name);
        $this->assertTrue(Hash::check('antiga1234', $aluno->password));
    }

    public function test_busca_aluno_por_nome_cpf_e_ra()
    {
        $ana = User::factory()->aluno()->create(['name' => 'Ana Busca']);
        User::factory()->aluno()->create(['name' => 'Bruno Outro']);

        $this->get('/coordenacao/alunos?busca=ana')->assertSee('Ana Busca')->assertDontSee('Bruno Outro');
        $this->get('/coordenacao/alunos?busca='.$ana->cpf_formatado)->assertSee('Ana Busca')->assertDontSee('Bruno Outro');
        $this->get('/coordenacao/alunos?busca='.$ana->ra)->assertSee('Ana Busca')->assertDontSee('Bruno Outro');
    }

    public function test_exclui_aluno()
    {
        $aluno = User::factory()->aluno()->create();
        $curso = Curso::factory()->create();
        $curso->alunos()->attach($aluno);

        $this->delete("/coordenacao/alunos/{$aluno->id}")->assertRedirect('/coordenacao/alunos');

        $this->assertModelMissing($aluno);
        $this->assertModelExists($curso);
        $this->assertDatabaseCount('curso_aluno', 0);
    }

    public function test_cadastra_edita_e_exclui_professor()
    {
        $this->post('/coordenacao/professores', [
            'name' => 'Paulo Freire Junior',
            'email' => 'paulo@email.test',
            'cpf' => '111.444.777-35',
            'password' => 'senhaforte1',
            'password_confirmation' => 'senhaforte1',
        ])->assertSessionHasNoErrors();

        $professor = User::firstWhere('email', 'paulo@email.test');
        $this->assertSame(User::PROFESSOR, $professor->papel);
        $this->assertNull($professor->ra);

        $this->put("/coordenacao/professores/{$professor->id}", [
            'name' => 'Paulo Freire', 'email' => 'paulo@email.test', 'cpf' => '11144477735',
        ])->assertSessionHasNoErrors();
        $this->assertSame('Paulo Freire', $professor->fresh()->name);

        $curso = Curso::factory()->create(['professor_id' => $professor->id]);
        $this->delete("/coordenacao/professores/{$professor->id}")->assertRedirect('/coordenacao/professores');

        $this->assertModelMissing($professor);
        $this->assertNull($curso->fresh()->professor_id); // o curso continua, so fica sem professor
    }

    public function test_telas_abrem()
    {
        $aluno = User::factory()->aluno()->create();
        $professor = User::factory()->professor()->create();

        foreach (['/coordenacao/alunos', '/coordenacao/alunos/create', "/coordenacao/alunos/{$aluno->id}/edit",
                  '/coordenacao/professores', '/coordenacao/professores/create', "/coordenacao/professores/{$professor->id}",
                  "/coordenacao/professores/{$professor->id}/edit"] as $url) {
            $this->get($url)->assertOk();
        }
    }
}
