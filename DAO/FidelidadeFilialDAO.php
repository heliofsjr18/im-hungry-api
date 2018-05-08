<?php

require_once 'Basics/FidelidadeFilial.php';
require_once 'Connection/Conexao.php';
class FidelidadeFilialDAO
{
    public function insert(FidelidadeFilial $fidelidadeFilial){

        $conn = \Database::conexao();
        $sql = "INSERT INTO empresa_cartao_fid (cartao_fid_qtd, cartao_fid_valor, cartao_fid_beneficio, filial_id)
                VALUES ( ?, ?, ?, ?);";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$fidelidadeFilial->getQtd(), PDO::PARAM_STR);
            $stmt->bindValue(2,$fidelidadeFilial->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(3,$fidelidadeFilial->getBeneficio(), PDO::PARAM_STR);
            $stmt->bindValue(4,$fidelidadeFilial->getFilialId(), PDO::PARAM_STR);
            $stmt->execute();

            return array(
                'status'    => 200,
                'message'   => "SUCCESS"
            );

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

    public function remove(FidelidadeFilial $fidelidadeFilial){

        $conn = \Database::conexao();
        $sql = "delete from empresa_cartao_fid where cartao_fid_id = ?;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$fidelidadeFilial->getId(), PDO::PARAM_STR);
            $stmt->execute();

            return array(
                'status'    => 200,
                'message'   => "SUCCESS"
            );

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