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
        $sql = "SELECT 	user_id, user_nome, user_senha, user_cpf, user_email, user_telefone,
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
        $sql = "SELECT 	user_id, user_nome, user_senha, user_cpf, user_email,
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

        //2 = funcionario, para salvar da web, caso não vai pro else(como tava)
        if($usuario->getTipoId()== "2"){
            $sql = "INSERT INTO usuarios (user_nome, user_cpf, user_telefone, user_email, user_senha, 
                                user_cep, user_endereco_numero, user_status, tipo_id, filial_id,
                                user_foto_perfil, user_data, user_cadastro)
                    VALUES ( ?, ?, ?, ?, SHA1(?), ?, ?, ?, ?, ?, ?, NOW(), NOW());";
        }else{
            $sql = "INSERT INTO usuarios (user_nome, user_email, user_senha, user_cadastro, 
                                user_foto_perfil, user_status, tipo_id)
                    VALUES ( ?, ?, SHA1(?), NOW(), ?, ?, ?);";
        }

        $stmt = $conn->prepare($sql);

        $enabled = ($usuario->getStatus() == 'true')? true : false;

        try {
            //2 = funcionario, para salvar da web, caso não vai pro else(como tava)
            if($usuario->getTipoId()== "2"){
                $stmt->bindValue(1,$usuario->getNome());
                $stmt->bindValue(2,$usuario->getCpf());
                $stmt->bindValue(3,$usuario->getTelefone());
                $stmt->bindValue(4,$usuario->getEmail());
                $stmt->bindValue(5,$usuario->getSenha());
                $stmt->bindValue(6,$usuario->getCep());
                $stmt->bindValue(7,$usuario->getEnderecoNumero());
                $stmt->bindValue(8,$enabled);
                $stmt->bindValue(9,$usuario->getTipoId());
                $stmt->bindValue(10,$usuario->getFilialId());
                $stmt->bindValue(11,$usuario->getFotoPerfil());
            }else{
                $stmt->bindValue(1,$usuario->getNome());
                $stmt->bindValue(2,$usuario->getEmail());
                $stmt->bindValue(3,$usuario->getSenha());
                $stmt->bindValue(4,$usuario->getFotoPerfil());
                $stmt->bindValue(5,$usuario->getStatus());
                $stmt->bindValue(6,$usuario->getTipoId());
            }

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

    public function update(Usuario $usuario){
        //Cria conexao
        $conn = \Database::conexao();

        $sql = "UPDATE usuarios 
                SET   user_nome = ?, 
                      user_cpf = ?, 
                      user_telefone = ?, 
                      user_email = ?, 
                      user_senha = ?,  
                      user_cep = ?, 
                      user_endereco_numero = ?, 
                      user_status = ?, 
                      tipo_id = ?, 
                      filial_id = ?, 
                      user_foto_perfil = ? 
                WHERE user_id = ?";
        $stmt = $conn->prepare($sql);

        $enabled = ($usuario->getStatus() == 'true')? true : false;

        try {
            $stmt->bindValue(1,$usuario->getNome());
            $stmt->bindValue(2,$usuario->getCpf());
            $stmt->bindValue(3,$usuario->getTelefone());
            $stmt->bindValue(4,$usuario->getEmail());
            $stmt->bindValue(5,$usuario->getSenha());
            $stmt->bindValue(6,$usuario->getCep());
            $stmt->bindValue(7,$usuario->getEnderecoNumero());
            $stmt->bindValue(8,$enabled);
            $stmt->bindValue(9,$usuario->getTipoId());
            $stmt->bindValue(10,$usuario->getFilialId());
            $stmt->bindValue(11,$usuario->getFotoPerfil());
            $stmt->bindValue(12,$usuario->getId());

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

    public function listAll(Usuario $usuario){

        $conn = \Database::conexao();
        $sql = "SELECT user_id, user_nome, user_cpf, user_email, user_senha, user_telefone, 
                       user_data, user_cadastro, user_foto_perfil, user_cep, user_endereco_numero,  
                       user_endereco_complemento, user_status, tipo_id, filial_id 
                FROM usuarios 
                WHERE tipo_id = ? 
                AND user_status = ? 
                AND filial_id = ?;";
        $stmt = $conn->prepare($sql);

        $enabled = ($usuario->getStatus() == 'true')? true : false;

        try {
            $stmt->bindValue(1,$usuario->getTipoId(), PDO::PARAM_INT);
            $stmt->bindValue(2,$enabled);
            $stmt->bindValue(3,$usuario->getFilialId(), PDO::PARAM_INT);
            $stmt->execute();
            $countLogin = $stmt->rowCount();
            $resultUsuario = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($countLogin != 0) {
                return $resultUsuario;
            }else{
                return array(
                    'status'    => 500,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Você não possui funcionarios cadastradas!'
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

    public function enabled(Usuario $usuario){
        //Cria conexao
        $conn = \Database::conexao();

        $sql = "UPDATE usuarios 
                SET   user_status = ?  
                WHERE user_id = ?";
        $stmt = $conn->prepare($sql);

        $enabled = ($usuario->getStatus() == 'true')? true : false;

        try {
            $stmt->bindValue(1,$enabled);
            $stmt->bindValue(2,$usuario->getId());

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