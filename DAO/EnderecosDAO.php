<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 29/03/18
 * Time: 06:37
 */

require_once 'Basics/Enderecos.php';
require_once 'Connection/Conexao.php';
class EnderecosDAO
{
    public function listCep(Enderecos $endereco){

        $conn = \Database::conexao();
        $sql = "SELECT id, cep, uf, cidade, bairro, logradouro, 
                       latitude, longitude, ibge_cod_uf, ibge_cod_cidade, 
                       area_cidade_km2, ddd
                FROM enderecos 
                WHERE cep = ?;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$endereco->getCep(), PDO::PARAM_INT);
            $stmt->execute();
            $countEndereco = $stmt->rowCount();
            $resultEnderecos = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($countEndereco != 0) {
                return $resultEnderecos;
            }else{
                return array(
                    'status'    => 500,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'CEP não encontrado!'
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