<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 12/06/2018
 * Time: 00:21
 */

require_once 'Basics/Usuario.php';
require_once 'DAO/FuncionarioDAO.php';

class FuncionarioController
{
    public function insert(Usuario $usuario){

        if (empty($usuario->getNome())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Nome do usuário não informado!');
            die;
        }
        if (empty($usuario->getCpf())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Cpf do usuário não informado!');
            die;
        }
        if (empty($usuario->getEmail())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'E-Mail não informado!');
            die;
        }
        if (empty($usuario->getSenha())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Senha não informada!');
            die;
        }
        if (empty($usuario->getData())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Data de Nascimento do usuário não informado!');
            die;
        }
        if (empty($usuario->getCep())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'CEP do usuário não informado!');
            die;
        }
        if (empty($usuario->getTelefone())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Telefone do usuário não informado!');
            die;
        }
        if (empty($usuario->getEnderecoNumero())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Número do endereço do usuário não informado!');
            die;
        }
        if (empty($usuario->getFilialId())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Número da filial do usuário não informado!');
            die;
        }

        $funcionarioDAO = new FuncionarioDAO();
        return $funcionarioDAO->insert($usuario);
    }

}