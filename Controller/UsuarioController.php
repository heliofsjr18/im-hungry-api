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
    public function login(Usuario $usuario){

        if ( empty($usuario->getEmail()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'E-Mail não informado!');
            die;
        }
        if ( empty($usuario->getSenha()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Senha não informada!');
            die;
        }
        if ( empty($usuario->getTipoId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Tipo não informado!');
            die;
        }

        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->loginUsuario($usuario);

    }
}