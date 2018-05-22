<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 05/03/2018
 * Time: 19:47
 */

require_once 'Basics/Usuario.php';
require_once 'DAO/UsuarioDAO.php';

class UsuarioController
{
    public function loginApp(Usuario $usuario)
    {

        if (empty($usuario->getEmail())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'E-Mail não informado!');
            die;
        }
        if (empty($usuario->getSenha())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Senha não informada!');
            die;
        }
        if (empty($usuario->getTipoId())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Tipo não informado!');
            die;
        }

        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->loginApp($usuario);

    }

    public function loginWeb(Usuario $usuario)
    {

        if (empty($usuario->getEmail())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'E-Mail não informado!');
            die;
        }
        if (empty($usuario->getSenha())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Senha não informada!');
            die;
        }

        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->loginWeb($usuario);

    }

    public function insert(Usuario $usuario)
    {

        //2 = funcionario, para salvar da web, caso não vai pro else(como tava)
        if ($usuario->getTipoId() == "2") {
            if (empty($usuario->getNome())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Nome do usuário não informado!');
                die;
            }
            if (empty($usuario->getCpf())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'CPF não informado!');
                die;
            }
            if (empty($usuario->getTelefone())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Telefone não informado!');
                die;
            }
            if (empty($usuario->getEmail())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Email não informado!');
                die;
            }
            if (empty($usuario->getSenha())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Senha não informada!');
                die;
            }
            if (empty($usuario->getCep())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Cep não informado!');
                die;
            }
            if (empty($usuario->getEnderecoNumero())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Número do endereço não informado!');
                die;
            }
            if (empty($usuario->getTipoId())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Id do tipo de usuario não informado!');
                die;
            }
            if (empty($usuario->getStatus())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Status não informado!');
                die;
            }
            if (empty($usuario->getFilialId())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Id da filial não informado!');
                die;
            }
            if (empty($usuario->getFotoPerfil())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Foto do perfil não informado!');
                die;
            }
        } else {
            if (empty($usuario->getNome())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Nome do usuário não informado!');
                die;
            }
            if (empty($usuario->getEmail())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'E-Mail não informado!');
                die;
            }
            if (empty($usuario->getSenha())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Senha não informada!');
                die;
            }
            if (empty($usuario->getFotoPerfil())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Foto não informada!');
                die;
            }
        }

        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->insert($usuario);
    }

    public function update(Usuario $usuario)
    {

        if ($usuario->getTipoId() == "2") {
            if (empty($usuario->getId())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Id do usuário não informado!');
                die;
            }
            if (empty($usuario->getNome())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Nome do usuário não informado!');
                die;
            }
            if (empty($usuario->getCpf())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'CPF não informado!');
                die;
            }
            if (empty($usuario->getTelefone())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Telefone não informado!');
                die;
            }
            if (empty($usuario->getEmail())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Email não informado!');
                die;
            }
            if (empty($usuario->getSenha())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Senha não informada!');
                die;
            }
            if (empty($usuario->getCep())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Cep não informado!');
                die;
            }
            if (empty($usuario->getEnderecoNumero())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Número do endereço não informado!');
                die;
            }
            if (empty($usuario->getTipoId())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Id do tipo de usuario não informado!');
                die;
            }
            if (empty($usuario->getStatus())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Status não informado!');
                die;
            }
            if (empty($usuario->getFilialId())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Id da filial não informado!');
                die;
            }
            if (empty($usuario->getFotoPerfil())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Foto do perfil não informado!');
                die;
            }
        } else {
            if (empty($usuario->getId())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Id não informado!');
                die;
            }
            if (empty($usuario->getNome())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Nome não informado!');
                die;
            }
            if (empty($usuario->getCpf())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'CPF não informado!');
                die;
            }
            if (empty($usuario->getEmail())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'E-mail não informado!');
                die;
            }
            if (empty($usuario->getTelefone())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Telefone não informado!');
                die;
            }
            if (empty($usuario->getData())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Data de Nascimento não informada!');
                die;
            }
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $usuario->getData())) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Formato de Data incorreto, espera-se YYYY-MM-DD!');
                die;
            }
        }
        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->update($usuario);
        //Enviar requisição para DAO

    }

    public function listAll(Usuario $usuario)
    {
        if (empty($usuario->getTipoId())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id do tipo do usuário não informado!');
            die;
        }
        if (empty($usuario->getStatus())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Enabled do usuário não definido!');
            die;
        }
        if (empty($usuario->getFilialId())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id da filial do usuário não definido!');
            die;
        }


        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->listAll($usuario);

    }
    public function enabled(Usuario $usuario){

        if ( empty($usuario->getId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id do usuário não informado!');
            die;
        }if ( empty($usuario->getStatus()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Enabled do usuário não informado!');
            die;
        }

        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->enabled($usuario);

    }
}