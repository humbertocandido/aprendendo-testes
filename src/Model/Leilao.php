<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance) : void
    {
        $this->validaUltimoLance($lance);
        if ($this->validaSeTemCincoLances($lance->getUsuario())) return;
        $this->lances[] = $lance;
    }

    /**
     * @throws \DomainException('Ofertante não pode enviar 2 lances seguidos!');
     * */ 
    private function validaUltimoLance($lance) : void
    {
        if (!empty($this->lances)) {
            $ultimoLance = $this->lances[array_key_last($this->lances)];
            if ($ultimoLance->getUsuario() == $lance->getUsuario()) 
                throw new \DomainException('Ofertante não pode enviar 2 lances seguidos!');
        }
    }

    /**
     * @throws \DomainException
     * */ 
    private function validaSeTemCincoLances($usuario)
    {   
        $qtd = 0;
        $totalDeLancesDeUsuario = array_reduce($this->lances, function($qtd, Lance $lance) use ($usuario) {
            if ($lance->getUsuario() == $usuario) return $qtd + 1;
            return $qtd;
        }, 0);

        if ($totalDeLancesDeUsuario >= 5) {
            throw new \DomainException('Ofertante não pode enviar mais que 5 lances por leilão!');
        }
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }
}
