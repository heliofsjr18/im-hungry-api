<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 20/03/18
 * Time: 01:37
 */

require_once 'Basics/MenuFilialItens.php';
require_once 'Connection/Conexao.php';
class MenuFilialItensDAO
{
    public function listAll(MenuFilialItens $menu){

        $conn = \Database::conexao();

        $enabled = ($menu->getStatus() == 'true')? true : false;

        $sql = "SELECT item_id, item_nome, item_valor, item_tempo_medio, item_status, item_promocao 
                FROM menu_filial_itens 
                WHERE item_status = ? 
                AND filial_id = ? 
                AND item_nome LIKE '%".$menu->getNome()."%';";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$enabled);
            $stmt->bindValue(2,$menu->getFilialId(), PDO::PARAM_INT);
            $stmt->execute();
            $countMenu = $stmt->rowCount();
            $resultItens = $stmt->fetchAll(PDO::FETCH_OBJ);


            if ($countMenu != 0) {
                $sql = "SELECT fot_id, fot_file, item_id 
                        FROM itens_fotos 
                        WHERE item_id = ?;";
                $stmt = $conn->prepare($sql);

                foreach ($resultItens as $key => $value){

                    $stmt->bindValue(1,$value->item_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $fotos = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $resultItens[$key]->fotos = $fotos;
                }

                return $resultItens;

            }else{
                return array(
                    'status'    => 500,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Este estabelecimento não possui itens no menu!'
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
}