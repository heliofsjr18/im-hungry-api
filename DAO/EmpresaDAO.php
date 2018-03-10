<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 08/03/18
 * Time: 23:53
 */

require_once 'Basics/Empresa.php';
require_once 'Connection/Conexao.php';
class EmpresaDAO
{
    public function insert(Empresa $empresa){

        $conn = \Database::conexao();
        $sql = "INSERT INTO empresa (empresa_nome, empresa_telefone, empresa_cnpj, empresa_cep, empresa_lat, empresa_long,
                                     empresa_numero_endereco, empresa_complemento_endereco, empresa_data_fundacao, 
                                     empresa_data_cadastro, empresa_foto_marca, empresa_facebook, empresa_instagram, 
                                     empresa_twitter, empresa_status, user_id)
                VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, TRUE, ?);";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$empresa->getNome(), PDO::PARAM_STR);
            $stmt->bindValue(2,$empresa->getTelefone(), PDO::PARAM_STR);
            $stmt->bindValue(3,$empresa->getCnpj(), PDO::PARAM_STR);
            $stmt->bindValue(4,$empresa->getCep(), PDO::PARAM_STR);
            $stmt->bindValue(5,$empresa->getLatitude(), PDO::PARAM_STR);
            $stmt->bindValue(6,$empresa->getLongitude(), PDO::PARAM_STR);
            $stmt->bindValue(7,$empresa->getNumeroEndereco(), PDO::PARAM_INT);
            $stmt->bindValue(8,$empresa->getComplementoEndereco(), PDO::PARAM_STR);
            $stmt->bindValue(9,$empresa->getDataFundacao(), PDO::PARAM_STR);
            $stmt->bindValue(10,$empresa->getFotoMarca(), PDO::PARAM_STR);
            $stmt->bindValue(11,$empresa->getFacebook(), PDO::PARAM_STR);
            $stmt->bindValue(12,$empresa->getInstagram(), PDO::PARAM_STR);
            $stmt->bindValue(13,$empresa->getTwitter(), PDO::PARAM_STR);
            $stmt->bindValue(14,$empresa->getUserId(), PDO::PARAM_INT);
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