<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 12/03/18
 * Time: 20:09
 */
require_once 'Basics/EmpresaFilial.php';
require_once 'Connection/Conexao.php';
class EmpresaFilialDAO
{
    public function listAll(EmpresaFilial $empresaFilial){

        $conn = \Database::conexao();
        $sql = "SELECT filial_id, filial_nome, filial_telefone, filial_cnpj, filial_cep, filial_lat, filial_long, 
                       filial_numero_endereco, filial_complemento_endereco, filial_status, empresa_id
                FROM empresa_filial 
                WHERE empresa_id = ?;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$empresaFilial->getEmpresaId(), PDO::PARAM_INT);
            $stmt->execute();
            $countLogin = $stmt->rowCount();
            $resultFiliais = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($countLogin != 0) {
                return $resultFiliais;
            }else{
                return array(
                    'status'    => 500,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Você não possui filiais cadastradas!'
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

    public function insert(EmpresaFilial $empresaFilial){

        $conn = \Database::conexao();
        $sql = "INSERT INTO empresa_filial (filial_nome, filial_telefone, filial_cnpj, filial_cep, filial_lat, filial_long,
                                     filial_numero_endereco, filial_complemento_endereco, filial_status, empresa_id)
                VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, TRUE, ?);";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$empresaFilial->getNome(), PDO::PARAM_STR);
            $stmt->bindValue(2,$empresaFilial->getTelefone(), PDO::PARAM_STR);
            $stmt->bindValue(3,$empresaFilial->getCnpj(), PDO::PARAM_STR);
            $stmt->bindValue(4,$empresaFilial->getCep(), PDO::PARAM_STR);
            $stmt->bindValue(5,$empresaFilial->getLatitude(), PDO::PARAM_STR);
            $stmt->bindValue(6,$empresaFilial->getLongitude(), PDO::PARAM_STR);
            $stmt->bindValue(7,$empresaFilial->getNumeroEndereco(), PDO::PARAM_INT);
            $stmt->bindValue(8,$empresaFilial->getComplementoEndereco(), PDO::PARAM_STR);
            $stmt->bindValue(9,$empresaFilial->getEmpresaId(), PDO::PARAM_STR);
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