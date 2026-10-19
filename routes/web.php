<?php

use App\Http\Controllers\Aluno\PainelController as AlunoPainel;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Coordenacao\AlunoController;
use App\Http\Controllers\Coordenacao\CursoController;
use App\Http\Controllers\Coordenacao\PainelController as CoordenacaoPainel;
use App\Http\Controllers\Coordenacao\ProfessorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\Professor\PainelController as ProfessorPainel;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas do portal
|--------------------------------------------------------------------------
| Nao existe cadastro publico: quem cadastra alunos e professores e a
| coordenacao, dentro do painel dela.
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware(['auth', 'PreventBackHistory'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/painel', [HomeController::class, 'painel'])->name('painel');

    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil');
    Route::put('/perfil/senha', [PerfilController::class, 'senha'])->name('perfil.senha');

    // Coordenacao: administra cursos, alunos e professores
    Route::middleware('papel:coordenacao')->prefix('coordenacao')->name('coordenacao.')->group(function () {
        Route::get('/', CoordenacaoPainel::class)->name('painel');
        Route::resource('cursos', CursoController::class);
        Route::resource('alunos', AlunoController::class);
        Route::resource('professores', ProfessorController::class)->parameters(['professores' => 'professor']);
    });

    // Professor: ve os cursos que leciona e as turmas
    Route::middleware('papel:professor')->prefix('professor')->name('professor.')->group(function () {
        Route::get('/', [ProfessorPainel::class, 'index'])->name('painel');
        Route::get('/cursos/{curso}', [ProfessorPainel::class, 'curso'])->name('curso');
    });

    // Aluno: ve os proprios cursos
    Route::middleware('papel:aluno')->prefix('aluno')->name('aluno.')->group(function () {
        Route::get('/', AlunoPainel::class)->name('painel');
    });
});
