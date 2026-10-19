<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Usuarios do portal: coordenacao, professores e alunos ficam na mesma
     * tabela e se diferenciam pela coluna "papel".
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('papel', 20)->index(); // coordenacao | professor | aluno
            $table->string('ra', 20)->nullable()->unique(); // matricula do aluno
            $table->string('cpf', 11)->nullable()->unique();
            $table->date('data_nascimento')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('filme_favorito')->nullable();
            $table->string('cep', 8)->nullable();
            $table->string('rua')->nullable();
            $table->string('numero', 20)->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf', 2)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
