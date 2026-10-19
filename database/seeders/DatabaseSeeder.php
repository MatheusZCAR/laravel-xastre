<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Dados de demonstracao. As senhas abaixo sao so para desenvolvimento:
     * troque-as (ou nao rode o seeder) em um servidor de verdade.
     */
    public function run()
    {
        User::factory()->coordenacao()->create([
            'name' => 'Secretaria Star-Fox',
            'email' => 'coordenacao@starfox.test',
            'password' => 'starfox123',
        ]);

        $professores = collect([
            ['Helena Duarte', 'professora@starfox.test'],
            ['Rafael Moreira', 'rafael.moreira@starfox.test'],
            ['Beatriz Nakamura', 'beatriz.nakamura@starfox.test'],
            ['Caio Albuquerque', 'caio.albuquerque@starfox.test'],
        ])->map(fn ($p) => User::factory()->professor()->create([
            'name' => $p[0],
            'email' => $p[1],
            'password' => 'starfox123',
        ]));

        $alunos = collect([User::factory()->aluno()->create([
            'name' => 'Lucas Ferreira',
            'email' => 'aluno@starfox.test',
            'password' => 'starfox123',
        ])])->merge(User::factory()->aluno()->count(23)->create());

        $cursos = [
            ['Lógica de Programação', 60, 'Primeiros passos para pensar como programador: algoritmos, variáveis, decisões e repetições.',
                "Algoritmos e fluxogramas\nVariáveis e tipos de dados\nEstruturas de decisão\nLaços de repetição\nVetores e matrizes"],
            ['Desenvolvimento Web com Laravel', 80, 'Construa aplicações web completas em PHP com rotas, banco de dados, autenticação e testes.',
                "Rotas e controllers\nBlade e componentes\nEloquent e migrations\nValidação e autenticação\nTestes automatizados"],
            ['Banco de Dados Relacional', 60, 'Modelagem de dados e SQL do básico ao intermediário, usando MySQL.',
                "Modelo entidade-relacionamento\nNormalização\nSELECT, JOIN e agregações\nÍndices e chaves estrangeiras"],
            ['Design de Interfaces', 40, 'Como criar telas claras e acessíveis: tipografia, cor, hierarquia e prototipação.',
                "Princípios de design visual\nTipografia e cor\nAcessibilidade\nProtótipos navegáveis"],
            ['Inglês para Tecnologia', 40, 'Leitura de documentação técnica e vocabulário do dia a dia de quem trabalha com TI.',
                "Leitura de documentação\nVocabulário técnico\nE-mails e reuniões"],
            ['Redes de Computadores', 60, 'Como os computadores conversam: endereçamento IP, protocolos e configuração de redes.',
                "Modelo OSI e TCP/IP\nEndereçamento IPv4 e IPv6\nDNS, DHCP e HTTP\nCabeamento e Wi-Fi"],
        ];

        foreach ($cursos as $i => [$nome, $horas, $descricao, $conteudo]) {
            $curso = Curso::create([
                'nome' => $nome,
                'carga_horaria' => $horas,
                'descricao' => $descricao,
                'conteudo' => $conteudo,
                // o ultimo curso fica sem professor, para mostrar esse caso na tela
                'professor_id' => $i < 5 ? $professores[$i % $professores->count()]->id : null,
            ]);
            $curso->alunos()->attach($alunos->random(random_int(6, 14))->pluck('id'));
        }

        // o aluno de demonstracao fica em dois cursos conhecidos
        $alunos->first()->cursosMatriculados()->syncWithoutDetaching(
            Curso::whereIn('nome', ['Lógica de Programação', 'Desenvolvimento Web com Laravel'])->pluck('id')
        );
    }
}
