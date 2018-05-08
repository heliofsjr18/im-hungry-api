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

        $sql1 = "update empresa_filial set filial_fidelidade = 1 where filial_id = ?";

        try {
            $stmt->bindValue(1,$fidelidadeFilial->getQtd(), PDO::PARAM_STR);
            $stmt->bindValue(2,$fidelidadeFilial->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(3,$fidelidadeFilial->getBeneficio(), PDO::PARAM_STR);
            $stmt->bindValue(4,$fidelidadeFilial->getFilialId(), PDO::PARAM_STR);
            $stmt->execute();

            $stmt1 = $conn->prepare($sql1);
            $stmt1->bindValue(1,$fidelidadeFilial->getFilialId(), PDO::PARAM_STR);
            $stmt1->execute();

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

        $sql1 = "update empresa_filial set filial_fidelidade = 0 where filial_id = ?";

        try {
            $stmt->bindValue(1,$fidelidadeFilial->getId(), PDO::PARAM_STR);
            $stmt->execute();

            $stmt1 = $conn->prepare($sql1);
            $stmt1->bindValue(1,$fidelidadeFilial->getFilialId(), PDO::PARAM_STR);
            $stmt1->execute();

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