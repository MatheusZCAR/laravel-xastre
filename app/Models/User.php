<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const COORDENACAO = 'coordenacao';
    public const PROFESSOR = 'professor';
    public const ALUNO = 'aluno';

    /**
     * Campos que podem ser preenchidos em massa. "papel" e "ra" ficam de fora
     * de proposito: sao definidos pelo sistema, nunca pelo que vem do formulario.
     */
    protected $fillable = [
        'name',
        'email',
        'cpf',
        'data_nascimento',
        'telefone',
        'filme_favorito',
        'cep',
        'rua',
        'numero',
        'bairro',
        'cidade',
        'uf',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'data_nascimento' => 'date',
    ];

    protected static function booted()
    {
        // o RA (matricula) do aluno e gerado automaticamente: ano + numero sequencial
        static::created(function (User $user) {
            if ($user->papel === self::ALUNO && ! $user->ra) {
                $user->ra = now()->format('Y').str_pad((string) $user->id, 5, '0', STR_PAD_LEFT);
                $user->saveQuietly();
            }
        });
    }

    /**
     * Senha sempre gravada com hash (o Laravel 9 ainda nao tem o cast "hashed").
     */
    public function setPasswordAttribute($valor)
    {
        $info = password_get_info((string) $valor);
        $this->attributes['password'] = $info['algo'] ? $valor : bcrypt($valor);
    }

    public function scopeAlunos(Builder $query): Builder
    {
        return $query->where('papel', self::ALUNO);
    }

    public function scopeProfessores(Builder $query): Builder
    {
        return $query->where('papel', self::PROFESSOR);
    }

    /** Busca por nome, e-mail, CPF ou RA. */
    public function scopeBusca(Builder $query, ?string $termo): Builder
    {
        $termo = trim((string) $termo);
        if ($termo === '') {
            return $query;
        }
        $digitos = preg_replace('/\D/', '', $termo);

        return $query->where(function (Builder $q) use ($termo, $digitos) {
            $q->where('name', 'like', "%{$termo}%")
              ->orWhere('email', 'like', "%{$termo}%");
            if ($digitos !== '') {
                $q->orWhere('cpf', 'like', "%{$digitos}%")
                  ->orWhere('ra', 'like', "%{$digitos}%");
            }
        });
    }

    public function isCoordenacao(): bool
    {
        return $this->papel === self::COORDENACAO;
    }

    public function isProfessor(): bool
    {
        return $this->papel === self::PROFESSOR;
    }

    public function isAluno(): bool
    {
        return $this->papel === self::ALUNO;
    }

    /** Cursos em que o aluno esta matriculado. */
    public function cursosMatriculados()
    {
        return $this->belongsToMany(Curso::class, 'curso_aluno', 'aluno_id', 'curso_id')
            ->withTimestamps()
            ->orderBy('nome');
    }

    /** Cursos que o professor leciona. */
    public function cursosLecionados()
    {
        return $this->hasMany(Curso::class, 'professor_id')->orderBy('nome');
    }

    /** Rota do painel de cada papel. */
    public function rotaPainel(): string
    {
        return match ($this->papel) {
            self::COORDENACAO => route('coordenacao.painel'),
            self::PROFESSOR => route('professor.painel'),
            default => route('aluno.painel'),
        };
    }

    public function getPapelNomeAttribute(): string
    {
        return match ($this->papel) {
            self::COORDENACAO => 'Coordenação',
            self::PROFESSOR => 'Professor(a)',
            default => 'Aluno(a)',
        };
    }

    public function getCpfFormatadoAttribute(): ?string
    {
        if (! $this->cpf) {
            return null;
        }

        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->cpf);
    }

    public function getCepFormatadoAttribute(): ?string
    {
        if (! $this->cep) {
            return null;
        }

        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $this->cep);
    }

    public function getIniciaisAttribute(): string
    {
        $partes = preg_split('/\s+/', trim($this->name));
        $primeira = mb_substr($partes[0] ?? '', 0, 1);
        $ultima = count($partes) > 1 ? mb_substr(end($partes), 0, 1) : '';

        return mb_strtoupper($primeira.$ultima);
    }
}
