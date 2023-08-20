<?php

namespace Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use DomainException;
use Tests\TestCase;

class AvaliadorTest extends TestCase
{
    private $avaliador;
    
    public function setUp() : void
    {
        $this->avaliador = new Avaliador;
    }
    
    /** 
     * @dataProvider criarLeilaoEmOrdemCrescente
     * @dataProvider criarLeilaoEmOrdemDecrescente
     * @dataProvider criarLeilaoEmOrdemAleatoria
     * */ 
    public function test_se_pegou_maior_lance(Leilao $leilao)
    {
        $this->avaliador->avalia($leilao);
        $maiorValor = $this->avaliador->getMaiorValor();

        self::assertEquals(2500, $maiorValor);
    }

    
    public function test_se_avaliador_joga_exception_quando_leilao_sem_lances()
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage('Não é possível avaliar leilão sem nenhum lance ofertado!');

        $leilao = new Leilao('Uma descrição de leilão sem lance');

        $this->avaliador->avalia($leilao);
    }

    /** 
     * @dataProvider criarLeilaoEmOrdemCrescente
     * @dataProvider criarLeilaoEmOrdemDecrescente
     * @dataProvider criarLeilaoEmOrdemAleatoria
     * */ 
    public function test_se_menor_lance_esta_correto(Leilao $leilao)
    {
        $this->avaliador->avalia($leilao);
        $maiorValor = $this->avaliador->getMenorValor();

        self::assertEquals(25, $maiorValor);
    }

    /** 
     * @dataProvider criarLeilaoEmOrdemCrescente
     * @dataProvider criarLeilaoEmOrdemDecrescente
     * @dataProvider criarLeilaoEmOrdemAleatoria
     * */ 
    public function test_3_maiores_lances(Leilao $leilao)
    {
        $this->avaliador->avalia($leilao);

        $maioresLances = $this->avaliador->getMaioresLances();

        self::assertCount(3, $maioresLances);
        self::assertEquals(2500, $maioresLances[0]->getValor());
        self::assertEquals(2000, $maioresLances[1]->getValor());
        self::assertEquals(1000, $maioresLances[2]->getValor());
    }

    
    public static function criarLeilaoEmOrdemCrescente()
    {
        $leilao = new Leilao('Uma descrição para o leilão.');
        $leilao->recebeLance(new Lance(new Usuario('Diogo'), 25));
        $leilao->recebeLance(new Lance(new Usuario('Fernanda'), 500));
        $leilao->recebeLance(new Lance(new Usuario('Dione'), 1000));
        $leilao->recebeLance(new Lance(new Usuario('Carlos'), 2000));
        $leilao->recebeLance(new Lance(new Usuario('Gabriel'), 2500));

        return [
            "Dados em ordem crescente" => [$leilao]
        ];
    }

    public static function criarLeilaoEmOrdemDecrescente()
    {
        $leilao = new Leilao('Uma descrição para o leilão.');
        $leilao->recebeLance(new Lance(new Usuario('Gabriel'), 2500));
        $leilao->recebeLance(new Lance(new Usuario('Carlos'), 2000));
        $leilao->recebeLance(new Lance(new Usuario('Dione'), 1000));
        $leilao->recebeLance(new Lance(new Usuario('Fernanda'), 500));
        $leilao->recebeLance(new Lance(new Usuario('Diogo'), 25));

        return [
            "Dados em ordem decrescente" => [$leilao]
        ];
    }

    public static function criarLeilaoEmOrdemAleatoria()
    {
        $leilao = new Leilao('Uma descrição para o leilão.');
        $leilao->recebeLance(new Lance(new Usuario('Carlos'), 2000));
        $leilao->recebeLance(new Lance(new Usuario('Diogo'), 25));
        $leilao->recebeLance(new Lance(new Usuario('Gabriel'), 2500));
        $leilao->recebeLance(new Lance(new Usuario('Dione'), 1000));
        $leilao->recebeLance(new Lance(new Usuario('Fernanda'), 500));

        return [
            "Dados em ordem aleatória" => [$leilao]
        ];
    }
}
