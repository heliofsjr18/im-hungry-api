<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 08/03/18
 * Time: 23:40
 */

require_once 'Basics/Empresa.php';
require_once 'DAO/EmpresaDAO.php';
class EmpresaController
{
    public function listAll(Empresa $empresa){
        if ( empty($empresa->getUserId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id do usuário não informado!');
            die;
        }if ( empty($empresa->getEnabled()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Enabled da Empresa não definido!');
            die;
        }

        $empresaDAO = new EmpresaDAO();
        return $empresaDAO->listAll($empresa);

    }

    public function insert(Empresa $empresa){

        if ( empty($empresa->getUserId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id do usuário não informado!');
            die;
        }if ( empty($empresa->getNome()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Nome não informado!');
            die;
        }
        if ( empty($empresa->getTelefone()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Telefone não informado!');
            die;
        }
        if ( empty($empresa->getCnpj()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'CNPJ não informado!');
            die;
        }
        if ( empty($empresa->getCep()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'CEP não informado!');
            die;
        }
        if ( empty($empresa->getNumeroEndereco()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Número do endereço não informado!');
            die;
        }
        if ( empty($empresa->getDataFundacao()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Data da Fundação não informada!');
            die;
        }if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$empresa->getDataFundacao())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Formato de Data incorreto, espera-se YYYY-MM-DD!');
            die;
        }

        $empresaDAO = new EmpresaDAO();
        return $empresaDAO->insert($empresa);

    }

    public function update(Empresa $empresa){

        if ( empty($empresa->getUserId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id do usuário não informado!');
            die;
        }if ( empty($empresa->getId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id da empresa não informado!');
            die;
        }if ( empty($empresa->getNome()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Nome não informado!');
            die;
        }
        if ( empty($empresa->getTelefone()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Telefone não informado!');
            die;
        }
        if ( empty($empresa->getCnpj()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'CNPJ não informado!');
            die;
        }
        if ( empty($empresa->getCep()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'CEP não informado!');
            die;
        }
        if ( empty($empresa->getNumeroEndereco()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Número do endereço não informado!');
            die;
        }
        if ( empty($empresa->getDataFundacao()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Data da Fundação não informada!');
            die;
        }if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$empresa->getDataFundacao())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Formato de Data incorreto, espera-se YYYY-MM-DD!');
            die;
        }

        $empresaDAO = new EmpresaDAO();
        return $empresaDAO->update($empresa);

    }

    public function enabled(Empresa $empresa){

        if ( empty($empresa->getId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id da empresa não informado!');
            die;
        }if ( empty($empresa->getEnabled()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Enabled da empresa não informado!');
            die;
        }

        $empresaDAO = new EmpresaDAO();
        return $empresaDAO->enabled($empresa);

    }
}