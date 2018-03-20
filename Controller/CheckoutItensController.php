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
    public function generate($array_itens, $array_qtd){
        if ( count($array_itens) == 0 ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Itens não informados!');
            die;
        }if ( count($array_qtd) == 0 ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Quantidade dos itens não informada!');
            die;
        }if ( count($array_itens) != count($array_qtd) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Arrays com tamanhos diferentes!');
            die;
        }

        $checkoutDAO = new CheckoutItensDAO();
        return $checkoutDAO->generate($array_itens, $array_qtd);

    }
}