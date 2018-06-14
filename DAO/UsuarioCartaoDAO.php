<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 28/05/2018
 * Time: 15:03
 */

require_once "Basics/UsuarioCartao.php";
require_once 'Connection/Conexao.php';

class UsuarioCartaoDAO
{
    public function listAll(UsuarioCartao $cartao){

        $conn = \Database::conexao();
        $sql = "SELECT cartao_id, cartao_digitos, cartao_ano, cartao_mes, cartao_brand, cartao_status, cartao_cvc 
                FROM clientes_cartao 
                WHERE user_id = ? 
                AND cartao_status = 1 
                ORDER BY cartao_id DESC;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$cartao->getUserId(), PDO::PARAM_INT);
            $stmt->execute();
            $countLogin = $stmt->rowCount();
            $resultCartao = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($countLogin != 0) {
                return $resultCartao;
            }else{
                return array(
                    'status'    => 500,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Você não possui cartões cadastrados!'
                );
            }

        } catch (PDOException $ex) {
            return array(
                'status'    => 500,
                'message'   => "ERROR",
                'result'    => 'Erro na execução da instrução!',
                'CODE'      => $ex->getCode(),
                'Exception' => $ex->getMessage(),
            );
        }

    }

    public function insert(UsuarioCartao $cartao){

        $conn = \Database::conexao();
        $sql = "INSERT INTO clientes_cartao (cartao_digitos, cartao_ano, cartao_mes, cartao_brand, cartao_status, user_id, cartao_cvc)
                    VALUES ( ?, ?, ?, ?, ?, ?, ?);";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$cartao->getDigitos(), PDO::PARAM_STR);
            $stmt->bindValue(2,$cartao->getAno(), PDO::PARAM_STR);
            $stmt->bindValue(3,$cartao->getMes(), PDO::PARAM_STR);
            $stmt->bindValue(4,$cartao->getBrand(), PDO::PARAM_STR);
            $stmt->bindValue(5,$cartao->getStatus());
            $stmt->bindValue(6,$cartao->getUserId(), PDO::PARAM_INT);
            $stmt->bindValue(7,$cartao->getCvc(), PDO::PARAM_INT);
            $stmt->execute();

            return $this->listAll($cartao);

        } catch (PDOException $ex) {
            return array(
                'status'    => 500,
                'message'   => "ERROR",
                'result'    => 'Erro na execução da instrução!',
                'CODE'      => $ex->getCode(),
                'Exception' => $ex->getMessage(),
            );
        }
    }

    public function enabled(UsuarioCartao $cartao){

        $conn = \Database::conexao();
        $sql = "UPDATE clientes_cartao SET cartao_status = 0
                WHERE cartao_id = ?;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$cartao->getId(), PDO::PARAM_INT);
            $stmt->execute();

            return $this->listAll($cartao);

        } catch (PDOException $ex) {
            return array(
                'status'    => 500,
                'message'   => "ERROR",
                'result'    => 'Erro na execução da instrução!',
                'CODE'      => $ex->getCode(),
                'Exception' => $ex->getMessage(),
            );
        }

    }

}