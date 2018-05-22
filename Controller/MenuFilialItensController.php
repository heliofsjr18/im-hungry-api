<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 20/03/18
 * Time: 01:18
 */

require_once 'Basics/MenuFilialItens.php';
require_once 'Basics/ItensFotos.php';
require_once 'DAO/MenuFilialItensDAO.php';
class MenuFilialItensController
{
    public function listAll(MenuFilialItens $menu){
        if ( empty($menu->getFilialId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID da Filial não informado!');
            die;
        }if ( empty($menu->getStatus()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Status não informado!');
            die;
        }

        $menuDAO = new MenuFilialItensDAO();
        return $menuDAO->listAll($menu);

    }

    public function insert(MenuFilialItens $itens, $itensFotos){
        if ( empty($itens->getNome()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Nome do item não informado!');
            die;
        }if ( empty($itens->getValor()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Valor do item não informado!');
            die;
        }if ( empty($itens->getTempoMedio()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Tempo médio do preparo não informado!');
            die;
        }if ( empty($itens->getPromocao()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Você precisa informar se o item está ou não na promoção!');
            die;
        }if ( empty($itens->getFilialId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID da Filial não informado!');
            die;
        }if ( empty($itensFotos) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Cadastre ao menos uma foto!');
            die;
        }

        $menuDAO = new MenuFilialItensDAO();
        return $menuDAO->insert($itens, $itensFotos);

    }

    public function update(MenuFilialItens $itens){
        if ( empty($itens->getId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID do item não informado!');
            die;
        }if ( empty($itens->getNome()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Nome do item não informado!');
            die;
        }if ( empty($itens->getValor()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Valor do item não informado!');
            die;
        }if ( empty($itens->getTempoMedio()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Tempo médio do preparo não informado!');
            die;
        }if ( empty($itens->getPromocao()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Você precisa informar se o item está ou não na promoção!');
            die;
        }

        $menuDAO = new MenuFilialItensDAO();
        return $menuDAO->update($itens);

    }

    public function addImage(ItensFotos $item){
        if ( empty($item->getItemId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID do item não informado!');
            die;
        }if ( empty($item->getFotFile()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Nome da imagem não informado!');
            die;
        }

        $menuDAO = new MenuFilialItensDAO();
        return $menuDAO->addImage($item);

    }

    public function delImage(ItensFotos $item){
        if ( empty($item->getId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID da imagem não informado!');
            die;
        }if ( empty($item->getItemId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID do item não informado!');
            die;
        }

        $menuDAO = new MenuFilialItensDAO();
        return $menuDAO->delImage($item);

    }

    public function enabled(MenuFilialItens $itens){

        if ( empty($itens->getId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Id do item não informado!');
            die;
        }if ( empty($itens->getStatus()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Enabled do item não informado!');
            die;
        }

        $menuDAO = new MenuFilialItensDAO();
        return $menuDAO->enabled($itens);

    }
}