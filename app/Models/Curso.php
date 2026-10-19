<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'conteudo',
        'carga_horaria',
        'professor_id',
    ];

    protected $casts = [
        'carga_horaria' => 'integer',
        'professor_id' => 'integer',
    ];

    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function alunos()
    {
        return $this->belongsToMany(User::class, 'curso_aluno', 'curso_id', 'aluno_id')
            ->withTimestamps()
            ->orderBy('name');
    }

    public function scopeBusca(Builder $query, ?string $termo): Builder
    {
        $termo = trim((string) $termo);
        if ($termo === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($termo) {
            $q->where('nome', 'like', "%{$termo}%")
              ->orWhere('descricao', 'like', "%{$termo}%");
        });
    }
}
