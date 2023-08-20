<?php

namespace Tests\Model;
use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use DomainException;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    
    /**
     * @dataProvider criandoArrayComLances
     * */ 
    public function test_total_lances_e_maior_lance(int $totalLances, Leilao $leilao, float $ultimoLance)
    {
        self::assertCount($totalLances, $leilao->getLances());
        self::assertEquals($ultimoLance, $leilao->getLances()[array_key_last($leilao->getLances())]->getValor());
    }

    public function test_lances_seguidos()
    {
        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Ofertante não pode enviar 2 lances seguidos!');

        $leilaoComUmLance = new Leilao('Camaro Amarelo');
        $leilaoComUmLance->recebeLance(new Lance(new Usuario('João'), 1000));
        $leilaoComUmLance->recebeLance(new Lance(new Usuario('João'), 5000));

    }

    
    public function test_maximo_5_lances_por_usuario()
    {   
        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Ofertante não pode enviar mais que 5 lances por leilão!');

        $leilao = new Leilao('Camaro Amarelo');
        $leilao->recebeLance(new Lance(new Usuario('João'), 1000));
        $leilao->recebeLance(new Lance(new Usuario('Maria'), 1200));
        $leilao->recebeLance(new Lance(new Usuario('João'), 1220));
        $leilao->recebeLance(new Lance(new Usuario('Maria'), 1230));
        $leilao->recebeLance(new Lance(new Usuario('João'), 1240));
        $leilao->recebeLance(new Lance(new Usuario('Maria'), 1250));
        $leilao->recebeLance(new Lance(new Usuario('João'), 1260));
        $leilao->recebeLance(new Lance(new Usuario('Maria'), 1270));
        $leilao->recebeLance(new Lance(new Usuario('João'), 1280));
        $leilao->recebeLance(new Lance(new Usuario('Maria'), 1500));

        $leilao->recebeLance(new Lance(new Usuario('João'), 2000));
    }

    
    public static function criandoArrayComLances()
    {
        $leilaoComUmLance = new Leilao('Camaro Amarelo');
        $leilaoComUmLance->recebeLance(new Lance(new Usuario('João'), 1000));

        $leilaoComDoisLances = new Leilao('Camaro Amarelo');
        $leilaoComDoisLances->recebeLance(new Lance(new Usuario('João'), 1000));
        $leilaoComDoisLances->recebeLance(new Lance(new Usuario('Maria'), 5000));

        return [
            "1-lance" => [1, $leilaoComUmLance, 1000],
            "2-lances" => [2, $leilaoComDoisLances, 5000],
        ];
    }
}
