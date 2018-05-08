<?php

require_once 'Basics/FidelidadeFilial.php';
require_once 'DAO/FidelidadeFilialDAO.php';
class FidelidadeFilialController
{
    public function insert(FidelidadeFilial $fidelidadeFilial){
        if ( empty($fidelidadeFilial->getQtd()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Quantidade não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getValor()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Valor não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getBeneficio()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Beneficio não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getFilialId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID da Filial não informado!');
            die;
        }

        $fidelidadeFilialDAO = new FidelidadeFilialDAO();
        return $fidelidadeFilialDAO->insert($fidelidadeFilial);

    }

    public function remove(FidelidadeFilial $fidelidadeFilial){
        if ( empty($fidelidadeFilial->getId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id da Fidelidade não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getFilialId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID da Filial não informado!');
            die;
        }

        $fidelidadeFilialDAO = new FidelidadeFilialDAO();
        return $fidelidadeFilialDAO->remove($fidelidadeFilial);

    }
}