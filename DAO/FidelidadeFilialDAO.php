<?php

require_once 'Basics/FidelidadeFilial.php';
require_once 'Connection/Conexao.php';
class FidelidadeFilialDAO
{
    public function listAll(FidelidadeFilial $fidelidadeFilial){

        $conn = \Database::conexao();

        $sql = "SELECT  cartao_fid_id, 
                        cartao_fid_nome, 
                        cartao_fid_qtd, 
                        cartao_fid_valor, 
                        cartao_fid_date,
                        cartao_fid_beneficio, 
                        cartao_fid_status, 
                        filial_id,  
						DATE_FORMAT(cartao_fid_date, '%d/%m/%Y') as data_format 
						
			      FROM empresa_cartao_fid
			      WHERE filial_id = ?
			      AND cartao_fid_status = ?;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$fidelidadeFilial->getFilialId(), PDO::PARAM_INT);
            $stmt->bindValue(2,$fidelidadeFilial->getStatus(), PDO::PARAM_INT);
            $stmt->execute();
            $countLogin = $stmt->rowCount();
            $resultFidelidade = $stmt->fetchAll(PDO::FETCH_OBJ);

            foreach ($resultFidelidade as $key => $value) {
                $resultFidelidade[$key]->cartao_fid_valor = number_format($resultFidelidade[$key]->cartao_fid_valor, 2, '.', '');
            }

            if ($countLogin != 0) {
                return $resultFidelidade;
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

    public function insert(FidelidadeFilial $fidelidadeFilial){

        $conn = \Database::conexao();
        $sql = "INSERT INTO empresa_cartao_fid (cartao_fid_nome, cartao_fid_qtd, cartao_fid_valor, cartao_fid_date, 
                                                cartao_fid_beneficio, cartao_fid_status, filial_id)
                VALUES ( ?, ?, ?, ?, ?, ?, ?);";
        $stmt = $conn->prepare($sql);

        //$sql1 = "update empresa_filial set filial_fidelidade = 1 where filial_id = ?";

        try {
            $stmt->bindValue(1,$fidelidadeFilial->getNome(), PDO::PARAM_STR);
            $stmt->bindValue(2,$fidelidadeFilial->getQtd(), PDO::PARAM_INT);
            $stmt->bindValue(3,$fidelidadeFilial->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(4,$fidelidadeFilial->getData(), PDO::PARAM_STR);
            $stmt->bindValue(5,$fidelidadeFilial->getBeneficio(), PDO::PARAM_STR);
            $stmt->bindValue(6,$fidelidadeFilial->getStatus(), PDO::PARAM_INT);
            $stmt->bindValue(7,$fidelidadeFilial->getFilialId(), PDO::PARAM_INT);
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

    public function update(FidelidadeFilial $fidelidadeFilial){

        $conn = \Database::conexao();
        $sql = "UPDATE empresa_cartao_fid 
                SET cartao_fid_nome = ?, 
                    cartao_fid_qtd = ?, 
                    cartao_fid_valor = ?,
                    cartao_fid_date = ?,
                    cartao_fid_beneficio = ?
                WHERE cartao_fid_id = ?";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$fidelidadeFilial->getNome(), PDO::PARAM_STR);
            $stmt->bindValue(2,$fidelidadeFilial->getQtd(), PDO::PARAM_INT);
            $stmt->bindValue(3,$fidelidadeFilial->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(4,$fidelidadeFilial->getData(), PDO::PARAM_STR);
            $stmt->bindValue(5,$fidelidadeFilial->getBeneficio(), PDO::PARAM_STR);
            $stmt->bindValue(6,$fidelidadeFilial->getId(), PDO::PARAM_STR);
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

    public function enabled(FidelidadeFilial $fidelidadeFilial){
        //Cria conexao
        $conn = \Database::conexao();

        $sql = "UPDATE empresa_cartao_fid 
                SET   cartao_fid_status = ?  
                WHERE cartao_fid_id = ?";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$fidelidadeFilial->getStatus(), PDO::PARAM_INT);
            $stmt->bindValue(2,$fidelidadeFilial->getId(), PDO::PARAM_INT);
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