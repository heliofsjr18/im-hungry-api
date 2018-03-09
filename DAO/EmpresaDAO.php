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
    public function create(Empresa $empresa){

        $conn = \Database::conexao();
        $sql = "INSERT INTO empresa (empresa_nome, empresa_telefone, empresa_cnpj, empresa_cep, 
                                     empresa_numero_endereco, empresa_complemento_endereco, empresa_data_fundacao, 
                                     empresa_data_cadastro, empresa_status, user_id)
                VALUES ( ?, ?, ?, ?, ?, ?, ?, NOW(), TRUE, ?);";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$empresa->getNome(), PDO::PARAM_STR);
            $stmt->bindValue(2,$empresa->getTelefone(), PDO::PARAM_STR);
            $stmt->bindValue(3,$empresa->getCnpj(), PDO::PARAM_STR);
            $stmt->bindValue(4,$empresa->getCep(), PDO::PARAM_STR);
            $stmt->bindValue(5,$empresa->getNumeroEndereco(), PDO::PARAM_INT);
            $stmt->bindValue(6,$empresa->getComplementoEndereco(), PDO::PARAM_STR);
            $stmt->bindValue(7,$empresa->getDataFundacao(), PDO::PARAM_STR);
            $stmt->bindValue(8,$empresa->getUserId(), PDO::PARAM_INT);
            $stmt->execute();

            return true;
            
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