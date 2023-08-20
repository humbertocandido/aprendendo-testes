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
        if (!$this->validaUltimoLance($lance)) return;
        if ($this->validaSeTemCincoLances($lance->getUsuario())) return;
        $this->lances[] = $lance;
    }

    
    private function validaUltimoLance($lance) : bool
    {
        if (!empty($this->lances)) {
            $ultimoLance = $this->lances[array_key_last($this->lances)];
            if ($ultimoLance->getUsuario() == $lance->getUsuario()) return false;
        }

        return true;
    }

    
    private function validaSeTemCincoLances($usuario)
    {   
        $qtd = 0;
        $totalDeLancesDeUsuario = array_reduce($this->lances, function($qtd, Lance $lance) use ($usuario) {
            if ($lance->getUsuario() == $usuario) return $qtd + 1;
            return $qtd;
        }, 0);

        return $totalDeLancesDeUsuario >= 5;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }
}
