<?php
/**
 * Created by PhpStorm.
 * User: r.a.freitas
 * Date: 12/06/2018
 * Time: 00:27
 */
require_once "Basics/Usuario.php";
require_once 'Connection/Conexao.php';
class FuncionarioDAO
{
    public function insert(Usuario $usuario){
        $conn = \Database::conexao();

        $query_foto = ( !empty($usuario->getFotoPerfil()) ) ? ',user_foto_perfil ' : '';

        $sql = "INSERT INTO usuarios (user_nome, user_cpf, user_email, user_senha, user_telefone, user_data, 
                                      user_cadastro, user_foto_perfil, user_cep, user_endereco_numero, 
                                      user_endereco_complemento, user_status, tipo_id, filial_id)
                    VALUES ( ?, ?, ?, SHA1(?), ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?);";
        $stmt = $conn->prepare($sql);

        try {

            $stmt->bindValue(1,$usuario->getNome());
            $stmt->bindValue(2,$usuario->getCpf());
            $stmt->bindValue(3,$usuario->getEmail());
            $stmt->bindValue(4,$usuario->getSenha());
            $stmt->bindValue(5,$usuario->getTelefone());
            $stmt->bindValue(6,$usuario->getData());

            $stmt->bindValue(7,$usuario->getFotoPerfil());
            $stmt->bindValue(8,$usuario->getCep());
            $stmt->bindValue(9,$usuario->getEnderecoNumero());
            $stmt->bindValue(10,$usuario->getEnderecoComplemento());
            $stmt->bindValue(11,$usuario->getStatus());
            $stmt->bindValue(12,$usuario->getTipoId());
            $stmt->bindValue(13,$usuario->getFilialId());
            $stmt->execute();

            return array(
                'status'        => 200,
                'message'       => "SUCCESS",
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