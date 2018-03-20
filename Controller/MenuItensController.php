<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 20/03/18
 * Time: 01:18
 */

require_once 'Basics/MenuFilialItens.php';
require_once 'DAO/MenuFilialItensDAO.php';
class MenuItensController
{
    public function listAll(MenuFilialItens $menu){
        if ( empty($menu->getFilialId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID da Filial não informado!');
            die;
        }

        $menuDAO = new MenuFilialItensDAO();
        return $menuDAO->listAll($menu);

    }
}