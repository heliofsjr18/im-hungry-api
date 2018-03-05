<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 05/03/2018
 * Time: 19:50
 */
require_once "Basics/Usuario.php";
require_once 'Connection/Conexao.php';
class UsuarioDAO
{
    public function loginUsuario(Usuario $usuario) {
        //Cria conexao
        $conn = \Database::conexao();
        //Cria SQL para inserir no banco
        $sql = "SELECT 	user_id, user_nome, user_cpf, user_email,
                        user_telefone, user_data, user_cadastro,
                        user_foto_perfil, user_status, tipo_id,
						DATE_FORMAT(user_data, '%d/%m/%Y') as dateAniversario, 
						DATE_FORMAT(user_cadastro, '%d/%m/%Y') as dateCadatro 
			  FROM usuarios 
			  WHERE user_email = ?
			  AND user_senha = sha1(?) 
			  AND tipo_id = ?
			  AND user_status = true 
			  LIMIT 1;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$usuario->getEmail());
            $stmt->bindValue(2,$usuario->getSenha());
            $stmt->bindValue(3,$usuario->getTipoId());
            $stmt->execute();
            $countLogin = $stmt->rowCount();
            $resultUsuario = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($countLogin != 1) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Usuário e/ou senha inválidos!');
            }else{
                return $resultUsuario;
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