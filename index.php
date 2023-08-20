<?php

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

    require_once "./vendor/autoload.php";
    
    $leilao = new Leilao('Uma descrição para o leilão.');
    $leilao->recebeLance(new Lance(new Usuario(''), 1000));
    $leilao->recebeLance(new Lance(new Usuario(''), 2000));
    $leilao->recebeLance(new Lance(new Usuario(''), 2500));
    $leilao->recebeLance(new Lance(new Usuario(''), 2500));
    $leilao->recebeLance(new Lance(new Usuario(''), 1000));
    $leilao->recebeLance(new Lance(new Usuario(''), 15));
    $leilao->recebeLance(new Lance(new Usuario(''), 75));
    $leilao->recebeLance(new Lance(new Usuario(''), 500));
    $leilao->recebeLance(new Lance(new Usuario(''), 2000));
    $leilao->recebeLance(new Lance(new Usuario(''), 2000));

    $avaliador = new Avaliador;
    $avaliador->avalia($leilao);

    $maioresLances = $avaliador->getMaioresLances();
    echo "<pre>";
    print_r($maioresLances);