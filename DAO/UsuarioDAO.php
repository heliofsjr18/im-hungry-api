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

    public function getUser($user_id){
        //Cria conexao
        $conn = \Database::conexao();
        //Cria SQL para inserir no banco
        $sql = "SELECT 	user_id, user_nome, user_cpf, user_email, user_telefone,
                        user_data, user_cadastro, user_foto_perfil, user_cep, tipo_id,
                        user_endereco_numero, user_endereco_complemento, user_status, 
						DATE_FORMAT(user_data, '%d/%m/%Y') as dateAniversario, 
						DATE_FORMAT(user_cadastro, '%d/%m/%Y') as dateCadastro 
			FROM usuarios WHERE user_id = ? LIMIT 1;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$user_id);
            $stmt->execute();
            $countLogin = $stmt->rowCount();
            $resultUsuario = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($countLogin != 1) {
                return false;
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

    public function loginApp(Usuario $usuario) {
        //Cria conexao
        $conn = \Database::conexao();
        //Cria SQL para inserir no banco
        $sql = "SELECT 	user_id, user_nome, user_cpf, user_email,
                        user_telefone, user_data, user_cadastro,
                        user_foto_perfil, user_status, tipo_id, filial_id,
						DATE_FORMAT(user_data, '%d/%m/%Y') as dateAniversario, 
						DATE_FORMAT(user_cadastro, '%d/%m/%Y') as dateCadastro 
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
            $resultUsuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($countLogin != 1) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Usuário e/ou senha inválidos!');
            }else{

                if($resultUsuario[0]['tipo_id'] == 3){

                    $sql = "SELECT cartao_id, cartao_digitos, cartao_ano, cartao_mes,
                                cartao_brand, cartao_status, cartao_cvc
                            FROM clientes_cartao 
                            WHERE user_id = ?
                            AND cartao_status = true;";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue(1,$resultUsuario[0]['user_id']);
                    $stmt->execute();
                    $countCards = $stmt->rowCount();
                    $getCards = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $resultUsuario[0]['credCards']['qtd'] = $countCards;
                    $resultUsuario[0]['credCards']['list'] = $getCards;

                }
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

    public function loginWeb(Usuario $usuario) {
        //Cria conexao
        $conn = \Database::conexao();
        //Cria SQL para inserir no banco
        $sql = "SELECT  user_id, user_nome, user_cpf, user_email,
                        user_telefone, user_data, user_cadastro,
                        user_foto_perfil, user_status, tipo_id, filial_id,
                        DATE_FORMAT(user_data, '%d/%m/%Y') as dateAniversario, 
                        DATE_FORMAT(user_cadastro, '%d/%m/%Y') as dateCadastro 
              FROM usuarios 
              WHERE user_email = ?
              AND user_senha = sha1(?) 
              AND tipo_id IN (1, 2) 
              AND user_status = true 
              LIMIT 1;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$usuario->getEmail());
            $stmt->bindValue(2,$usuario->getSenha());
            $stmt->execute();
            $countLogin = $stmt->rowCount();
            $resultUsuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($countLogin != 1) {
                return array('status' => 204, 'message' => "ERROR", 'result' => 'Usuário e/ou senha inválidos!');
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

    public function insert(Usuario $usuario){
        $conn = \Database::conexao();
        $sql = "INSERT INTO usuarios (user_nome, user_email, user_senha, user_cadastro, 
                                      user_foto_perfil, user_status, tipo_id)
                VALUES ( ?, ?, SHA1(?), NOW(), ?, ?, ?);";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$usuario->getNome());
            $stmt->bindValue(2,$usuario->getEmail());
            $stmt->bindValue(3,$usuario->getSenha());
            $stmt->bindValue(4,$usuario->getFotoPerfil());
            $stmt->bindValue(5,$usuario->getStatus());
            $stmt->bindValue(6,$usuario->getTipoId());
            $stmt->execute();
            $last_id = $conn->lastInsertId();

            return $this->getUser($last_id);

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

    public  function update(Usuario $usuario){
        //Cria conexao
        $conn = \Database::conexao();

        $query_senha = ( !empty($usuario->getSenha()) ) ? ',user_senha = sha1(?)' : '';
        $query_foto = ( !empty($usuario->getFotoPerfil()) ) ? ',user_foto_perfil = ?' : '';

        $sql = "UPDATE aluno
                SET  user_nome  = ?,
                     user_cpf = ?,
                     user_email = ?,
                     user_telefone = ?,
                     user_data = ?
                     ".$query_senha."
                     ".$query_foto."
                WHERE user_id = ?";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$usuario->getNome());
            $stmt->bindValue(2,$usuario->getCpf());
            $stmt->bindValue(3,$usuario->getEmail());
            $stmt->bindValue(4,$usuario->getTelefone());
            $stmt->bindValue(5,$usuario->getData());

            $aux = 6;
            if (!empty($query_senha)){
                $stmt->bindValue($aux,$usuario->getSenha());
                $aux++;
            }if (!empty($query_foto)){
                $stmt->bindValue($aux,$usuario->getFotoPerfil());
                $aux++;
            }
            $stmt->bindValue($aux,$usuario->getId());

            if ($stmt->execute()){
                return $this->getUser($usuario->getId());
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