<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 12/03/18
 * Time: 21:43
 */

require_once 'Basics/MenuPadrao.php';
require_once 'Connection/Conexao.php';
class MenuPadraoDAO
{
    public function listAll(MenuPadrao $menuPadrao){

        $bool = ($menuPadrao->getStatus() == 'true') ? true : false;

        $conn = \Database::conexao();
        $sql = "SELECT menu_id, menu_nome, menu_status, empresa_id 
                FROM menu_padrao 
                WHERE menu_status = ? 
                AND empresa_id = ?;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$bool);
            $stmt->bindValue(2,$menuPadrao->getEmpresaId(), PDO::PARAM_INT);
            $stmt->execute();
            $countMenu = $stmt->rowCount();
            $resultFiliais = $stmt->fetchAll(PDO::FETCH_OBJ);


            if ($countMenu != 0) {
                $sql = "SELECT item_id, item_nome, item_valor, item_tempo_medio, item_status, item_promocao, menu_id 
                        FROM menu_padrao_itens 
                        WHERE item_status = TRUE 
                        AND menu_id = ?;";
                $stmt = $conn->prepare($sql);

                foreach ($resultFiliais as $key => $value){

                    $stmt->bindValue(1,$value->menu_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $resultItens = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $resultFiliais[$key]->itens = $resultItens;
                }

                return $resultFiliais;

            }else{
                return array(
                    'status'    => 500,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Você não possui Padrões de Menu cadastrados!'
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

    public function insert(MenuPadrao $menu, ArrayObject $array_itens){

        $conn = \Database::conexao();
        $sql = "INSERT INTO menu_padrao (menu_nome, menu_status, empresa_id)
                VALUES ( ?, TRUE, ?);";
        $stmt = $conn->prepare($sql);

        $sql = "INSERT INTO menu_padrao_itens (item_nome, item_valor, item_tempo_medio, 
                                               item_status, item_promocao, menu_id)
                VALUES ( ?, ?, ?, TRUE, ?, ?);";
        $stmt2 = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$menu->getNome(), PDO::PARAM_STR);
            $stmt->bindValue(2,$menu->getEmpresaId(), PDO::PARAM_INT);
            $stmt->execute();
            $last_id = $conn->lastInsertId();

            foreach ($array_itens as $key => $value){

                $bool = ($value->getPromocao() == 'true') ? true : false;

                $stmt2->bindValue(1,$value->getNome(), PDO::PARAM_STR);
                $stmt2->bindValue(2,$value->getValor(), PDO::PARAM_STR);
                $stmt2->bindValue(3,$value->getTempoMedio(), PDO::PARAM_STR);
                $stmt2->bindValue(4,$bool);
                $stmt2->bindValue(5,$last_id, PDO::PARAM_INT);
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
}