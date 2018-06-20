<?php

require_once 'Basics/DescontoFilial.php';
require_once 'DAO/DescontoFilialDAO.php';
class DescontoFilialController
{
    public function listAll(DescontoFilial $descontoFilial){

        if ( empty($descontoFilial->getStatus()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Status do cartão não informado!');
            die;
        }
        if ( empty($descontoFilial->getFilialId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID da Filial não informado!');
            die;
        }

        $descontoFilialDAO = new DescontoFilialDAO();
        return $descontoFilialDAO->listAll($descontoFilial);

    }

    public function insert(DescontoFilial $descontoFilial){
        if ( empty($descontoFilial->getValor()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Valor não informado!');
            die;
        }
        if ( empty($descontoFilial->getData()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Data não informada!');
            die;
        }
        if ( empty($descontoFilial->getBeneficio()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Beneficio não informado!');
            die;
        }
        if ( empty($descontoFilial->getStatus()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Statu não informado!');
            die;
        }
        if ( empty($descontoFilial->getFilialId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID da Filial não informado!');
            die;
        }

        $descontoFilialDAO = new DescontoFilialDAO();
        return $descontoFilialDAO->insert($descontoFilial);

    }

    public function update(DescontoFilial $descontoFilial){
        if ( empty($descontoFilial->getId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID não informado!');
            die;
        }
        if ( empty($descontoFilial->getValor()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Valor não informado!');
            die;
        }
        if ( empty($descontoFilial->getData()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Data não informada!');
            die;
        }
        if ( empty($descontoFilial->getBeneficio()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Beneficio não informado!');
            die;
        }

        $descontoFilialDAO = new DescontoFilialDAO();
        return $descontoFilialDAO->update($descontoFilial);

    }

    public function enabled(DescontoFilial $descontoFilial){
        if ( empty($descontoFilial->getId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id do Desconto não informado!');
            die;
        }
        if ( empty($descontoFilial->getStatus()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Status não informado!');
            die;
        }
        if ( empty($descontoFilial->getFilialId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID da filial não informado!');
            die;
        }

        $descontoFilialDAO = new DescontoFilialDAO();
        return $descontoFilialDAO->enabled($descontoFilial);

    }

    public function historico($user_id){
        if ( empty($user_id) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id do usuário não informado!');
            die;
        }

        $descontoFilialDAO = new DescontoFilialDAO();
        return $descontoFilialDAO->historico($user_id);

    }
}