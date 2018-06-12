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

    public function listApp($lat, $long, $search, $fidelidade){
        if ( empty($lat)){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Latitude não Informada!');
            die;
        }if ( empty($long)){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Longitude não Informada!');
            die;
        }if ( empty($fidelidade)){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Parâmetro de fidelidade não Informado!');
            die;
        }

        $empresaFilialDAO = new EmpresaFilialDAO();
        return $empresaFilialDAO->listApp($lat, $long, $search, $fidelidade);

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
        if ( empty($empresaFilial->getEmpresaId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID da Empresa não informado!');
            die;
        }

        $empresaFilialDAO = new EmpresaFilialDAO();
        return $empresaFilialDAO->insert($empresaFilial);
    }

    public function update(EmpresaFilial $empresaFilial){

        if ( empty($empresaFilial->getId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id da filial não informado!');
            die;
        }if ( empty($empresaFilial->getNome()) ){
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
        if ( empty($empresaFilial->getEmpresaId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id da empresa não informado!');
            die;
        }

        $empresaDAO = new EmpresaFilialDAO();
        return $empresaDAO->update($empresaFilial);

    }

    public function enabled(EmpresaFilial $empresaFilial){

        if ( empty($empresaFilial->getId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id da filial não informado!');
            die;
        }if ( empty($empresaFilial->getEnabled()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Enabled da filial não informado!');
            die;
        }

        $empresaDAO = new EmpresaFilialDAO();
        return $empresaDAO->enabled($empresaFilial);

    }

    public function status(EmpresaFilial $empresaFilial){

        if ( empty($empresaFilial->getId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id da filial não informado!');
            die;
        }if ( empty($empresaFilial->getStatus()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Status da filial não informado!');
            die;
        }

        $empresaDAO = new EmpresaFilialDAO();
        return $empresaDAO->status($empresaFilial);

    }

}