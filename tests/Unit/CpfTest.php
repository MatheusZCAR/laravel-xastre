<?php

namespace Tests\Unit;

use App\Rules\Cpf;
use Database\Factories\UserFactory;
use PHPUnit\Framework\TestCase;

class CpfTest extends TestCase
{
    public function test_aceita_cpf_valido_com_ou_sem_pontuacao()
    {
        $regra = new Cpf();
        $this->assertTrue($regra->passes('cpf', '529.982.247-25'));
        $this->assertTrue($regra->passes('cpf', '52998224725'));
    }

    public function test_recusa_cpf_invalido()
    {
        $regra = new Cpf();
        foreach (['529.982.247-24', '111.111.111-11', '123', '', 'abc.def.ghi-jk', '5299822472500'] as $cpf) {
            $this->assertFalse($regra->passes('cpf', $cpf), "CPF {$cpf} deveria ser recusado");
        }
    }

    public function test_cpfs_gerados_pela_factory_sao_validos()
    {
        $regra = new Cpf();
        for ($i = 0; $i < 200; $i++) {
            $this->assertTrue($regra->passes('cpf', UserFactory::cpfValido()));
        }
    }
}
