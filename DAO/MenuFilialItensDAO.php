<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 20/03/18
 * Time: 01:37
 */

require_once 'Basics/MenuFilialItens.php';
require_once 'Basics/ItensFotos.php';
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

                    $resultItens[$key]->item_valor = number_format($resultItens[$key]->item_valor, 2, '.', '');

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

    public function insert(MenuFilialItens $itens, $itensFotos){

        $conn = \Database::conexao();

        $enabled = ($itens->getPromocao() == 'true')? true : false;

        $sql = "INSERT INTO menu_filial_itens (item_nome, item_valor, item_tempo_medio, item_status, item_promocao, filial_id)
                VALUES ( ?, ?, ?, TRUE, ?, ?);";

        $sql2 = "INSERT INTO itens_fotos (fot_file, item_id)
                VALUES ( ?, ?);";

        $stmt = $conn->prepare($sql);
        $stmt2 = $conn->prepare($sql2);

        try {
            $stmt->bindValue(1,$itens->getNome(), PDO::PARAM_STR);
            $stmt->bindValue(2,$itens->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(3,$itens->getTempoMedio(), PDO::PARAM_STR);
            $stmt->bindValue(4,$enabled);
            $stmt->bindValue(5,$itens->getFilialId(), PDO::PARAM_INT);
            $stmt->execute();

            $last_id = $conn->lastInsertId();

            foreach ($itensFotos as $key => $value){
                $stmt2->bindValue(1,$value, PDO::PARAM_STR);
                $stmt2->bindValue(2,$last_id, PDO::PARAM_INT);
                $stmt2->execute();
            }

            return array(
                'status'    => 200,
                'message'   => "SUCCESS"
            );

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

    public function update(MenuFilialItens $itens){

        $conn = \Database::conexao();

        $enabled = ($itens->getPromocao() == 'true')? true : false;

        $sql = "UPDATE menu_filial_itens
                SET  item_nome  = ?,
                     item_valor = ?,
                     item_tempo_medio = ?,
                     item_promocao = ?
                WHERE item_id = ?";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$itens->getNome(), PDO::PARAM_STR);
            $stmt->bindValue(2,$itens->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(3,$itens->getTempoMedio(), PDO::PARAM_STR);
            $stmt->bindValue(4,$enabled);
            $stmt->bindValue(5,$itens->getId(), PDO::PARAM_INT);
            $stmt->execute();

            return array(
                'status'    => 200,
                'message'   => "SUCCESS"
            );

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

    public function addImage(ItensFotos $item){

        $conn = \Database::conexao();

        $sql = "INSERT INTO itens_fotos (fot_file, item_id)
                VALUES ( ?, ?);";

        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$item->getFotFile(), PDO::PARAM_STR);
            $stmt->bindValue(2,$item->getItemId(), PDO::PARAM_INT);
            $stmt->execute();

            return array(
                'status'    => 200,
                'message'   => "SUCCESS"
            );

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

    public function delImage(ItensFotos $item){

        $conn = \Database::conexao();

        $sql = "SELECT fot_id from itens_fotos WHERE item_id = ?";
        $stmt = $conn->prepare($sql);

        $sql2 = "DELETE from itens_fotos WHERE fot_id = ?";
        $stmt2 = $conn->prepare($sql2);

        try {

            $stmt->bindValue(1,$item->getItemId(), PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->rowCount();

            if ($count != 1){
                $stmt2->bindValue(1,$item->getId(), PDO::PARAM_INT);
                $stmt2->execute();

                return array(
                    'status'    => 200,
                    'message'   => "SUCCESS"
                );

            }else{
                return array(
                    'status'    => 500,
                    'message'   => "ERROR",
                    'result'    => "Cada item deve ter no mínimo uma imagem!"
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

    public function enabled(MenuFilialItens $itens){
        //Cria conexao
        $conn = \Database::conexao();

        $sql = "UPDATE menu_filial_itens 
                SET   item_status = ?  
                WHERE item_id = ?";
        $stmt = $conn->prepare($sql);

        $enabled = ($itens->getStatus() == 'true')? true : false;

        try {
            $stmt->bindValue(1,$enabled);
            $stmt->bindValue(2,$itens->getId());
            $stmt->execute();

            return array(
                'status'    => 200,
                'message'   => "SUCCESS"
            );

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