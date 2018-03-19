<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 10/03/18
 * Time: 01:38
 */

require_once 'Basics/EmpresaFilial.php';
require_once 'DAO/EmpresaFilialDAO.php';
class EmpresaFilialController
{
    public function listAll($user_id){
        if ( empty($user_id)){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID do Usuário não informado!');
            die;
        }

        $empresaFilialDAO = new EmpresaFilialDAO();
        return $empresaFilialDAO->listAll($user_id);

    }

    public function insert(EmpresaFilial $empresaFilial){
        if ( empty($empresaFilial->getNome()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Nome não informado!');
            die;
        }
        if ( empty($empresaFilial->getTelefone()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Telefone não informado!');
            die;
        }
        if ( empty($empresaFilial->getCnpj()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'CNPJ não informado!');
            die;
        }
        if ( empty($empresaFilial->getCep()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'CEP não informado!');
            die;
        }
        if ( empty($empresaFilial->getNumeroEndereco()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Número do endereço não informado!');
            die;
        }
        if ( empty($empresaFilial->getComplementoEndereco()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Complemento não informado!');
            die;
        }if ( empty($empresaFilial->getEmpresaId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID da Empresa não informado!');
            die;
        }

        $empresaFilialDAO = new EmpresaFilialDAO();
        return $empresaFilialDAO->insert($empresaFilial);
    }
}