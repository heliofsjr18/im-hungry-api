<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 12/03/18
 * Time: 20:09
 */
require_once 'Basics/EmpresaFilial.php';
require_once 'Connection/Conexao.php';
class EmpresaFilialDAO
{
    public function listAll($user_id, $status){

        $conn = \Database::conexao();

        $sql1 = "SELECT empresa_id FROM empresa WHERE user_id = ?;";
        $stmt1 = $conn->prepare($sql1);


        $sql2 = "SELECT fil.filial_id, 
                        fil.filial_nome, 
                        fil.filial_telefone, 
                        fil.filial_cnpj, 
                        fil.filial_cep, 
                        fil.filial_lat, 
                        fil.filial_long, 
                        fil.filial_numero_endereco, 
                        fil.filial_complemento_endereco, 
                        fil.filial_status, 
                        fil.filial_enabled,
                        
                        emp.empresa_id,
                        emp.empresa_nome
                  
                  FROM empresa_filial fil
                  INNER JOIN empresa emp
                  on emp.empresa_id = fil.empresa_id
                  WHERE fil.empresa_id = ?  
                  AND fil.filial_enabled = ?
                  ORDER BY fil.filial_id;";
        $stmt2 = $conn->prepare($sql2);

        try {
            $enabled = ($status == 'true')? true : false;

            $stmt1->bindValue(1,$user_id, PDO::PARAM_INT);
            $stmt1->execute();
            $ids_empresas = $stmt1->fetchAll(PDO::FETCH_OBJ);

            $array_filiais = [];


            foreach ($ids_empresas as $key => $value){
                $stmt2->bindValue(1,$value->empresa_id, PDO::PARAM_INT);
                $stmt2->bindValue(2,$enabled);
                $stmt2->execute();
                $filiais = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                foreach ($filiais as $key2 => $value2) {
                    array_push($array_filiais, $value2);
                }

            }

            $count = count($array_filiais);

            if ($count == 0){
                return array(
                    'status'    => 500,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Você não possui filiais cadastradas!'
                );
            }else{
                return $array_filiais;
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

    public function listApp($lat, $long, $search){

        $conn = \Database::conexao();

        $sql1 = "SELECT F.filial_id, F.filial_nome, F.filial_status, F.filial_numero_endereco, E.logradouro, E.bairro, E.cidade, E.uf, EM.empresa_foto_marca, 4 as avaliacao,
                  ( SELECT 6371 * acos( cos( radians(?) ) * cos( radians( E.latitude ) ) * cos( radians( ? ) - radians(E.longitude) ) + sin( radians(?) ) * sin( radians( E.latitude ) ) )) as distancia
                 FROM empresa_filial F
	             INNER JOIN enderecos E
			     ON F.filial_cep = E.cep
			     INNER JOIN empresa EM
			     ON F.empresa_id = EM.empresa_id
                 WHERE F.empresa_id IS NOT NULL 
                 AND F.filial_nome LIKE '%".$search."%'";
        $stmt1 = $conn->prepare($sql1);

        try {
            $stmt1->bindValue(1,$lat, PDO::PARAM_STR);
            $stmt1->bindValue(2,$long, PDO::PARAM_STR);
            $stmt1->bindValue(3,$lat, PDO::PARAM_STR);

            $stmt1->execute();

            $result = $stmt1->fetchAll(PDO::FETCH_OBJ);
            $count = count($result);

            if ($count == 0){
                return array(
                    'status'    => 500,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Nenhuma filial encontrada!'
                );
            }else{
                return $result;
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

    public function insert(EmpresaFilial $empresaFilial){

        $conn = \Database::conexao();
        $sql = "INSERT INTO empresa_filial (filial_nome, filial_telefone, filial_cnpj, filial_cep, filial_lat, filial_long,
                                     filial_numero_endereco, filial_complemento_endereco, filial_status, empresa_id, filial_enabled)
                VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, TRUE, ?, 0);";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$empresaFilial->getNome(), PDO::PARAM_STR);
            $stmt->bindValue(2,$empresaFilial->getTelefone(), PDO::PARAM_STR);
            $stmt->bindValue(3,$empresaFilial->getCnpj(), PDO::PARAM_STR);
            $stmt->bindValue(4,$empresaFilial->getCep(), PDO::PARAM_STR);
            $stmt->bindValue(5,$empresaFilial->getLatitude(), PDO::PARAM_STR);
            $stmt->bindValue(6,$empresaFilial->getLongitude(), PDO::PARAM_STR);
            $stmt->bindValue(7,$empresaFilial->getNumeroEndereco(), PDO::PARAM_INT);
            $stmt->bindValue(8,$empresaFilial->getComplementoEndereco(), PDO::PARAM_STR);
            $stmt->bindValue(9,$empresaFilial->getEmpresaId(), PDO::PARAM_STR);
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

    public function update(EmpresaFilial $empresaFilial){

        $conn = \Database::conexao();

        $sql = "UPDATE empresa
                SET  filial_nome  = ?,
                     filial_telefone = ?,
                     filial_cnpj = ?,
                     filial_cep = ?,
                     filial_lat = ?,
                     filial_long = ?,
                     filial_numero_endereco = ?,
                     filial_complemento_endereco = ?,
                     empresa_id = ?
                WHERE filial_id = ?";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$empresaFilial->getNome(), PDO::PARAM_STR);
            $stmt->bindValue(2,$empresaFilial->getTelefone(), PDO::PARAM_STR);
            $stmt->bindValue(3,$empresaFilial->getCnpj(), PDO::PARAM_STR);
            $stmt->bindValue(4,$empresaFilial->getCep(), PDO::PARAM_STR);
            $stmt->bindValue(5,$empresaFilial->getLatitude(), PDO::PARAM_STR);
            $stmt->bindValue(6,$empresaFilial->getLongitude(), PDO::PARAM_STR);
            $stmt->bindValue(7,$empresaFilial->getNumeroEndereco(), PDO::PARAM_INT);
            $stmt->bindValue(8,$empresaFilial->getComplementoEndereco(), PDO::PARAM_STR);
            $stmt->bindValue(9,$empresaFilial->getEmpresaId(), PDO::PARAM_STR);
            $stmt->bindValue(9,$empresaFilial->getId(), PDO::PARAM_STR);
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

    public function enabled(EmpresaFilial $empresaFilial){

        $conn = \Database::conexao();

        $sql = "UPDATE empresa_filial
                SET  filial_enabled  = ?
                WHERE filial_id = ?";
        $stmt = $conn->prepare($sql);

        $enabled = ($empresaFilial->getEnabled() == 'true')? true : false ;

        try {
            $stmt->bindValue(1,$enabled);
            $stmt->bindValue(2,$empresaFilial->getId(), PDO::PARAM_INT);
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