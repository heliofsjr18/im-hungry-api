<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 28/05/2018
 * Time: 15:02
 */

require_once 'Basics/UsuarioCartao.php';
require_once 'DAO/UsuarioCartaoDAO.php';

class UsuarioCartaoController
{
    public function listAll(UsuarioCartao $cartao)
    {
        if (empty($cartao->getUserId())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id do usuário não informado!');
            die;
        }

        $cartaoDAO = new UsuarioCartaoDAO();
        return $cartaoDAO->listAll($cartao);

    }

    public function insert(UsuarioCartao $cartao)
    {
        if (empty($cartao->getDigitos())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Os dígitos do cartão não foram informados!');
            die;
        }
        if (empty($cartao->getAno())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'O ano não foi informado!');
            die;
        }
        if (empty($cartao->getMes())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'O mês não foi informado!');
            die;
        }
        if (empty($cartao->getBrand())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Bandeira do cartão não informada!');
            die;
        }
        if (empty($cartao->getStatus())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Status não informado!');
            die;
        }
        if (empty($cartao->getUserId())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id do usuário não informado!');
            die;
        }


        $cartaoDAO = new UsuarioCartaoDAO();
        return $cartaoDAO->listAll($cartao);

    }

    public function enabled(UsuarioCartao $cartao)
    {
        if (empty($cartao->getId())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID do cartão não informado!');
            die;
        }
        if (empty($cartao->getUserId())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id do usuário não informado!');
            die;
        }

        $cartaoDAO = new UsuarioCartaoDAO();
        return $cartaoDAO->enabled($cartao);
    }

}