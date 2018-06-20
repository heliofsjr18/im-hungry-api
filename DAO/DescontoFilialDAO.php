<?php

require_once 'Basics/DescontoFilial.php';
require_once 'Connection/Conexao.php';
class DescontoFilialDAO
{
    public function listAll(DescontoFilial $descontoFilial){

        $conn = \Database::conexao();

        $sql = "SELECT  cupom_id, 
                        cupom_valor, 
                        cupom_validade,
                        cupom_desc, 
                        cupom_status, 
                        filial_id
			      FROM cupom_desconto
			      WHERE filial_id = ?
			      AND cupom_status = ?;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$descontoFilial->getFilialId(), PDO::PARAM_INT);
            $stmt->bindValue(2,$descontoFilial->getStatus(), PDO::PARAM_INT);
            $stmt->execute();
            $countLogin = $stmt->rowCount();
            $resultDesconto = $stmt->fetchAll(PDO::FETCH_OBJ);

            foreach ($resultDesconto as $key => $value) {
                $resultDesconto[$key]->cupom_valor = number_format($resultDesconto[$key]->cupom_valor, 2, '.', '');
            }

            if ($countLogin != 0) {
                return $resultDesconto;
            }else{
                return array(
                    'status'    => 500,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Você não possui cupons cadastrados!'
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

    public function insert(DescontoFilial $descontoFilial){

        $conn = \Database::conexao();
        $sql = "INSERT INTO cupom_desconto (cupom_valor, cupom_validade, cupom_desc, cupom_status, filial_id) VALUES (?, ?, ?, ?, ?);";
        $stmt = $conn->prepare($sql);

        //$sql1 = "update empresa_filial set filial_fidelidade = 1 where filial_id = ?";

        try {
            $stmt->bindValue(1,$descontoFilial->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(2,$descontoFilial->getData(), PDO::PARAM_STR);
            $stmt->bindValue(3,$descontoFilial->getBeneficio(), PDO::PARAM_STR);
            $stmt->bindValue(4,$descontoFilial->getStatus(), PDO::PARAM_INT);
            $stmt->bindValue(5,$descontoFilial->getFilialId(), PDO::PARAM_INT);
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

    public function update(DescontoFilial $descontoFilial){

        $conn = \Database::conexao();
        $sql = "UPDATE cupom_desconto 
                SET cupom_valor = ?,
                    cupom_validade = ?,
                    cupom_desc = ?
                WHERE cupom_id = ?";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(3,$descontoFilial->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(4,$descontoFilial->getData(), PDO::PARAM_STR);
            $stmt->bindValue(5,$descontoFilial->getBeneficio(), PDO::PARAM_STR);
            $stmt->bindValue(6,$descontoFilial->getId(), PDO::PARAM_STR);
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

    public function enabled(DescontoFilial $descontoFilial){
        //Cria conexao
        $conn = \Database::conexao();

        $sql = "SELECT  cupom_id  
			      FROM cupom_desconto
			      WHERE filial_id = ? 
			      AND cupom_status = 2;";
        $stmt = $conn->prepare($sql);

        $sql1 = "UPDATE cupom_desconto 
                SET   cupom_status = ?  
                WHERE cupom_id = ?";
        $stmt1 = $conn->prepare($sql1);

        $sql2 = "UPDATE empresa_filial set filial_cupom = 1 where filial_id = ?";
        $stmt2 = $conn->prepare($sql2);


        try {

            $stmt->bindValue(1,$descontoFilial->getFilialId(), PDO::PARAM_INT);
            $stmt->execute();
            $countLogin = $stmt->rowCount();

            if ($countLogin !== 0) {

                return array(
                    'status'    => 500,
                    'message'   => "INFO",
                    'result'    => 'Você já possui um programa de cupons ativo!'
                );


            }else{

                $stmt1->bindValue(1,$descontoFilial->getStatus(), PDO::PARAM_INT);
                $stmt1->bindValue(2,$descontoFilial->getId(), PDO::PARAM_INT);
                $stmt1->execute();

                $stmt2->bindValue(1,$descontoFilial->getFilialId(), PDO::PARAM_INT);
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
						
                        (SELECT COUNT(*) FROM clientes_pontos_fid WHERE cupom_id = cart.cupom_id ) AS total,
                        cart.cupom_id,
                        cart.cupom_valor,
                        cart.cupom_validade,
                        DATE_FORMAT( cart.cupom_validade , '%d/%m/%Y às %H:%i:%s' ) AS fid_date_format, 
                        cart.cupom_desc,
                        
                        fili.filial_nome,
                        emp.empresa_foto_marca
                        
			      FROM clientes_pontos_fid pot
			      INNER JOIN checkout chec 
			      ON chec.checkout_id = pot.checkout_id
			      
			      INNER JOIN cupom_desconto cart
			      ON cart.cupom_id = pot.cupom_id
			      
			      INNER JOIN empresa_filial fili
			      ON fili.filial_id = cart.filial_id
			      
			      INNER JOIN empresa emp
			      ON emp.empresa_id = fili.empresa_id
			      
			      WHERE pot.user_id = ?
			      ORDER BY pot.cupom_id ASC, pot.cliente_ponto_id ASC;";
        $stmt = $conn->prepare($sql);

        try {

            $stmt->bindValue(1,$user_id, PDO::PARAM_INT);
            $stmt->execute();
            $countLogin = $stmt->rowCount();

            if ($countLogin !== 0) {

                $result = $stmt->fetchAll(PDO::FETCH_OBJ);

                $group = array();
                $desconto = array();

                // GroupBy - cupom_id
                foreach ($result as $value) {
                    $group[$value->cupom_id] = $value;
                }

                foreach ($group as $key => $value) {

                    $temp = array(
                        'cupom_id' => $value->cupom_id,
                        'nome_filial' => $value->filial_nome,
                        'foto_filial' => $value->empresa_foto_marca,
                        'cupom_nome' => $value->cupom_nome,
                        'cupom_validade' => $value->fid_date_format,
                        'cupom_desc' => $value->cupom_desc,
                        'requisito_valor' => $value->cupom_valor,
                        'historico_pontos' => array()

                    );

                    array_push($desconto, $temp);

                }

                foreach ($desconto as $key => $value) {
                    foreach ($result as $key2 => $value2) {
                        if ($value['cupom_id'] == $value2->cupom_id) {
                            $historico = array(
                                'checkout_ref' => $value2->checkout_ref,
                                'checkout_data' => $value2->checkout_date_format,
                                'checkout_valor' => $value2->checkout_valor_bruto
                            );
                            array_push($desconto[$key]['historico_pontos'], $historico);
                        }
                    }
                }

                return $desconto;

            }else{

                return array(
                    'status'    => 500,
                    'message'   => "INFO",
                    'result'    => 'Você não possui nenhuma pontuação em programas de cupons de desconto!'
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