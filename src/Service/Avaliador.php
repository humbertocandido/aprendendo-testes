<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;

class Avaliador
{
    private $maiorValor = -INF;
    private $menorValor = INF;
    /** 
     * @var Lance[]
     * */ 
    private array $maioresLances = [];

    public function avalia(Leilao $leilao): void
    {
        if (!$leilao->getLances()) throw new \DomainException('Não foi dado nenhum lance!');

        foreach ($leilao->getLances() as $lance) {
            if ($lance->getValor() >= $this->maiorValor)
                $this->maiorValor = $lance->getValor();
            if ($lance->getValor() <= $this->menorValor)
                $this->menorValor = $lance->getValor();
        }

        $lances = $leilao->getLances();

        usort($lances, function($lance1, $lance2) {
            return ($lance1->getValor() - $lance2->getValor()) * -1;
        });

        $this->maioresLances = array_slice($lances, 0, 3);
    }

    public function getMaiorValor(): float
    {
        return $this->maiorValor;
    }

    public function getMenorValor(): float
    {
        return $this->menorValor;
    }

    
    public function getMaioresLances() : array
    {
        return $this->maioresLances;
    }
}
