<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 29/03/18
 * Time: 06:30
 */

require_once 'Basics/Enderecos.php';
require_once 'DAO/EnderecosDAO.php';
class EnderecosController
{
    public function listCep(Enderecos $endereco){
        if ( empty($endereco->getCep()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'CEP não informado!');
            die;
        }

        $enderecosDAO = new EnderecosDAO();
        return $enderecosDAO->listCep($endereco);

    }
}