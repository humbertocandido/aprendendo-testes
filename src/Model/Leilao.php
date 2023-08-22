<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;
    /** @var bool */ 
    private $finalizado;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->finalizado = false;
    }

    public function recebeLance(Lance $lance) : void
    {
        if ($this->estaFinalizado()) throw new \DomainException('Leilão finalizado não pode receber lances!');

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
    private function validaSeTemCincoLances($usuario) : void
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

    public function finalizar() : void
    {
        $this->finalizado = true;
    }

    
    public function estaFinalizado() : bool
    {
        return $this->finalizado;
    }
}
