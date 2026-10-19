<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao');
            $table->text('conteudo')->nullable(); // ementa: o que sera estudado
            $table->unsignedSmallInteger('carga_horaria'); // em horas
            $table->foreignId('professor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // matriculas: quais alunos estao em quais cursos
        Schema::create('curso_aluno', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('aluno_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['curso_id', 'aluno_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('curso_aluno');
        Schema::dropIfExists('cursos');
    }
};
