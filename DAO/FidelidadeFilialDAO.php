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

        $sql = "SELECT  cartao_fid_id  
			      FROM empresa_cartao_fid
			      WHERE filial_id = ? 
			      AND cartao_fid_status = 2;";
        $stmt = $conn->prepare($sql);

        $sql1 = "UPDATE empresa_cartao_fid 
                SET   cartao_fid_status = ?  
                WHERE cartao_fid_id = ?";
        $stmt1 = $conn->prepare($sql1);

        $sql2 = "UPDATE empresa_filial set filial_fidelidade = 1 where filial_id = ?";
        $stmt2 = $conn->prepare($sql2);


        try {

            $stmt->bindValue(1,$fidelidadeFilial->getFilialId(), PDO::PARAM_INT);
            $stmt->execute();
            $countLogin = $stmt->rowCount();

            if ($countLogin !== 0) {

                return array(
                    'status'    => 500,
                    'message'   => "INFO",
                    'result'    => 'Você já possui um programa de fidelidade ativo!'
                );


            }else{

                $stmt1->bindValue(1,$fidelidadeFilial->getStatus(), PDO::PARAM_INT);
                $stmt1->bindValue(2,$fidelidadeFilial->getId(), PDO::PARAM_INT);
                $stmt1->execute();

                $stmt2->bindValue(1,$fidelidadeFilial->getFilialId(), PDO::PARAM_INT);
                $stmt2->execute();

                return array(
                    'status'    => 200,
                    'message'   => "SUCCESS"
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

    public function historico($user_id){
        //Cria conexao
        $conn = \Database::conexao();

        $sql = "SELECT  pot.cliente_ponto_id, 
                        pot.user_id, 
                       	
                        chec.checkout_id, 
                        chec.checkout_ref, 
                        DATE_FORMAT( chec.checkout_date , '%d/%m/%Y às %H:%i:%s' ) AS checkout_date_format, 
                        chec.checkout_date, 
                        chec.checkout_valor_bruto, 
						
                        (SELECT COUNT(*) FROM clientes_pontos_fid WHERE cartao_fid_id = cart.cartao_fid_id ) AS total,
                        cart.cartao_fid_id,
                        cart.cartao_fid_nome,
                        cart.cartao_fid_qtd,
                        cart.cartao_fid_valor,
                        cart.cartao_fid_date,
                        DATE_FORMAT( cart.cartao_fid_date , '%d/%m/%Y às %H:%i:%s' ) AS fid_date_format, 
                        cart.cartao_fid_beneficio,
                        
                        fili.filial_nome,
                        emp.empresa_foto_marca
                        
			      FROM clientes_pontos_fid pot
			      INNER JOIN checkout chec 
			      ON chec.checkout_id = pot.checkout_id
			      
			      INNER JOIN empresa_cartao_fid cart
			      ON cart.cartao_fid_id = pot.cartao_fid_id
			      
			      INNER JOIN empresa_filial fili
			      ON fili.filial_id = cart.filial_id
			      
			      INNER JOIN empresa emp
			      ON emp.empresa_id = fili.empresa_id
			      
			      WHERE pot.user_id = ?
			      ORDER BY pot.cartao_fid_id ASC, pot.cliente_ponto_id ASC;";
        $stmt = $conn->prepare($sql);

        try {

            $stmt->bindValue(1,$user_id, PDO::PARAM_INT);
            $stmt->execute();
            $countLogin = $stmt->rowCount();

            if ($countLogin !== 0) {

                $result = $stmt->fetchAll(PDO::FETCH_OBJ);

                $group = array();
                $fidelidade = array();

                // GroupBy - cartao_fid_id
                foreach ($result as $value) {
                    $group[$value->cartao_fid_id] = $value;
                }

                foreach ($group as $key => $value) {

                    $temp = array(
                        'cartao_id' => $value->cartao_fid_id,
                        'nome_filial' => $value->filial_nome,
                        'foto_filial' => $value->empresa_foto_marca,
                        'cartao_nome' => $value->cartao_fid_nome,
                        'cartao_validade' => $value->fid_date_format,
                        'cartao_beneficio' => $value->cartao_fid_beneficio,
                        'pontos_conquistados' => $value->total,
                        'pontos_necessarios' => $value->cartao_fid_qtd,
                        'requisito_valor' => $value->cartao_fid_valor,
                        'historico_pontos' => array()

                    );

                    array_push($fidelidade, $temp);

                }

                foreach ($fidelidade as $key => $value) {
                    foreach ($result as $key2 => $value2) {
                        if ($value['cartao_id'] == $value2->cartao_fid_id) {
                            $historico = array(
                                'checkout_ref' => $value2->checkout_ref,
                                'checkout_data' => $value2->checkout_date_format,
                                'checkout_valor' => $value2->checkout_valor_bruto
                            );
                            array_push($fidelidade[$key]['historico_pontos'], $historico);
                        }
                    }
                }

                return $fidelidade;

            }else{

                return array(
                    'status'    => 500,
                    'message'   => "INFO",
                    'result'    => 'Você não possui nenhuma pontuação em programas de fidelidade!'
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

}