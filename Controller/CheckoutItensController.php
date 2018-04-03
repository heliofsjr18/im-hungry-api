<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 20/03/18
 * Time: 03:21
 */
require_once 'DAO/CheckoutItensDAO.php';
class CheckoutItensController
{
    public function generate($array_itens, $array_qtd, $token, $hash, $user_id){
        if ( count($array_itens) == 0 ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Itens não informados!');
            die;
        }if ( count($array_qtd) == 0 ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Quantidade dos itens não informada!');
            die;
        }if ( count($array_itens) != count($array_qtd) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Arrays com tamanhos diferentes!');
            die;
        }if ( empty($token) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Token do cartão não informado!');
            die;
        }if ( empty($hash) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Hash da sessão não informado!');
            die;
        }

        $checkoutDAO = new CheckoutItensDAO();
        return $checkoutDAO->generate($array_itens, $array_qtd, $token, $hash, $user_id);

    }

    public function notification($code, $status, $referencia, $disponivel, $lastEventDate){

        if ( empty($code) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Código da Transação não informado!');
            die;
        }if ( empty($status) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Status da Transação não informado!');
            die;
        }if ( empty($referencia) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Referencia da Transação não informado!');
            die;
        }if ( empty($disponivel) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Disponiblidade não informada!');
            die;
        }if ( empty($lastEventDate) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Última atualização não informada!');
            die;
        }

        $checkoutDAO = new CheckoutItensDAO();
        return $checkoutDAO->notification($code, $status, $referencia, $disponivel, $lastEventDate);
    }

    public function consult($ref, $user_id){

        if ( empty($ref) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Código de referência não informado!');
            die;
        }if ( empty($user_id) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id do usuário não informado!');
            die;
        }
        $checkoutDAO = new CheckoutItensDAO();
        return $checkoutDAO->consult($ref, $user_id);
    }

    public function listAll(){
        $checkoutDAO = new CheckoutItensDAO();
        return $checkoutDAO->listAll();
    }
}