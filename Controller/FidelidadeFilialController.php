<?php

require_once 'Basics/FidelidadeFilial.php';
require_once 'DAO/FidelidadeFilialDAO.php';
class FidelidadeFilialController
{
    public function listAll(FidelidadeFilial $fidelidadeFilial){

        if ( empty($fidelidadeFilial->getStatus()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Status do cartão não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getFilialId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID da Filial não informado!');
            die;
        }

        $fidelidadeFilialDAO = new FidelidadeFilialDAO();
        return $fidelidadeFilialDAO->listAll($fidelidadeFilial);

    }

    public function insert(FidelidadeFilial $fidelidadeFilial){
        if ( empty($fidelidadeFilial->getNome()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Nome não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getQtd()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Quantidade não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getValor()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Valor não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getData()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Data não informada!');
            die;
        }
        if ( empty($fidelidadeFilial->getBeneficio()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Beneficio não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getStatus()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Statu não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getFilialId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID da Filial não informado!');
            die;
        }

        $fidelidadeFilialDAO = new FidelidadeFilialDAO();
        return $fidelidadeFilialDAO->insert($fidelidadeFilial);

    }

    public function update(FidelidadeFilial $fidelidadeFilial){
        if ( empty($fidelidadeFilial->getId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getNome()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Nome não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getQtd()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Quantidade não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getValor()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Valor não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getData()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Data não informada!');
            die;
        }
        if ( empty($fidelidadeFilial->getBeneficio()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Beneficio não informado!');
            die;
        }

        $fidelidadeFilialDAO = new FidelidadeFilialDAO();
        return $fidelidadeFilialDAO->update($fidelidadeFilial);

    }

    public function enabled(FidelidadeFilial $fidelidadeFilial){
        if ( empty($fidelidadeFilial->getId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id da Fidelidade não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getStatus()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Status não informado!');
            die;
        }
        if ( empty($fidelidadeFilial->getFilialId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID da filial não informado!');
            die;
        }

        $fidelidadeFilialDAO = new FidelidadeFilialDAO();
        return $fidelidadeFilialDAO->enabled($fidelidadeFilial);

    }
}