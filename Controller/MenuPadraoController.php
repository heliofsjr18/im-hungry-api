<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 12/03/18
 * Time: 21:31
 */

require_once 'Basics/MenuPadrao.php';
require_once 'DAO/MenuPadraoDAO.php';
class MenuPadraoController
{
    public function insert(MenuPadrao $menu, ArrayObject $array_itens){
        if ( empty($menu->getNome()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Nome não informado!');
            die;
        }if ( empty($menu->getEmpresaId()) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'ID da Empresa não informado!');
            die;
        }

        foreach ($array_itens as $key => $value){

            if ( empty($value->getNome()) ){
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe o Nome do Item!');
                die;
            }if ( empty($value->getValor()) ){
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe o Valor do Item!');
                die;
            }if ( empty($value->getTempoMedio()) ){
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe o Tempo Médio do Item!');
                die;
            }if ( empty($value->getPromocao()) ){
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe se o item está na promoção!');
                die;
            }

        }

        $menuDAO = new MenuPadraoDAO();
        return $menuDAO->insert($menu, $array_itens);
    }
}