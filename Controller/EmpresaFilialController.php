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
    public function listAll($user_id, $status){
        if ( empty($user_id)){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID do Usuário não informado!');
            die;
        }if ( empty($status)){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Status não informado!');
            die;
        }

        $empresaFilialDAO = new EmpresaFilialDAO();
        return $empresaFilialDAO->listAll($user_id, $status);

    }

    public function listApp($lat, $long, $search){
        if ( empty($lat)){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Latitude não Informada!');
            die;
        }if ( empty($long)){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Longitude não Informada!');
            die;
        }

        $empresaFilialDAO = new EmpresaFilialDAO();
        return $empresaFilialDAO->listApp($lat, $long, $search);

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