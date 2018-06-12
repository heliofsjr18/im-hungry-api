<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 20/03/18
 * Time: 07:13
 */

require_once 'Connection/Conexao.php';
require_once "lib/vendor/autoload.php";

use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Topic;
use sngrl\PhpFirebaseCloudMessaging\Notification;

class CheckoutItensDAO
{
    public function generate($array_itens, $array_qtd, $token, $hash, $user_id, $cartao_id){

        $conn = \Database::conexao();
        $sql = "SELECT item_id, item_nome, item_valor, filial_id
                FROM menu_filial_itens 
                WHERE item_id = ?;";
        $stmt = $conn->prepare($sql);

        $sql2 = "SELECT usu.user_id, 
                        usu.user_nome, 
                        usu.user_cpf, 
                        usu.user_email, 
                        usu.user_telefone, 
                        usu.user_data,
                        usu.user_cadastro, 
                        usu.user_foto_perfil, 
                        usu.user_cep,  
                        usu.user_endereco_numero,  
                        usu.user_endereco_complemento,  
                        usu.user_status, 
                        usu.tipo_id,
						DATE_FORMAT(usu.user_data, '%d/%m/%Y') as dateAniversario, 
						DATE_FORMAT(usu.user_cadastro, '%d/%m/%Y') as dateCadastro, 
						
						ende.logradouro,
						ende.bairro,
						ende.cidade,
						ende.uf
						
			      FROM usuarios usu
			      INNER JOIN enderecos ende
			      ON usu.user_cep = ende.cep
			      
			      WHERE usu.user_id = ? LIMIT 1;";
        $stmt2 = $conn->prepare($sql2);

        try {
            $stmt2->bindValue(1,$user_id);
            $stmt2->execute();
            $userData = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            $userData = $userData[0];
            $carCompleto = [];
            $totalCompra = 0;
            $filialId = [];

            foreach ($array_itens as $key => $value) {

                $stmt->bindValue(1,$value, PDO::PARAM_INT);
                $stmt->execute();
                $itemCompleto = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $itemCompleto[0]['qtd'] = $array_qtd[$key];
                $totalCompra += ($itemCompleto[0]['item_valor'] * $array_qtd[$key]);
                array_push($carCompleto, $itemCompleto[0]);
                array_push($filialId, $itemCompleto[0]['filial_id']);

            }

            if (count(array_unique($filialId)) != 1 ){
                return array(
                    'status'        => 500,
                    'message'       => "ERROR",
                    'result'        => 'Itens de filiais diferentes!',
                    'description'   => 'Somente são permitidos a compra de itens de uma única filial por vez!'
                );
                die;
            }

            $filial_id = $filialId[0];
            $totalCompra = number_format($totalCompra, 2, '.', '');

        } catch (PDOException $ex) {
            return array(
                'status'    => 500,
                'message'   => "ERROR",
                'result'    => 'Erro na execução da instrução!',
                'CODE'      => $ex->getCode(),
                'Exception' => $ex->getMessage(),
            );
        }

        try{

            //Chamada PagSeguro

            \PagSeguro\Library::initialize();
            \PagSeguro\Library::cmsVersion()->setName("ImHungry")->setRelease("1.0.0");
            \PagSeguro\Library::moduleVersion()->setName("ImHungry")->setRelease("1.0.0");

            $creditCard = new \PagSeguro\Domains\Requests\DirectPayment\CreditCard();
            $ref = strtoupper(uniqid());

            $creditCard->setReceiverEmail('pagseguro@rafafreitas.com');
            $creditCard->setReference($ref);
            $creditCard->setCurrency("BRL");

            foreach ($carCompleto as $key => $value) {

                $aux = $key + 1;
                $creditCard->addItems()->withParameters(
                    $aux,
                    $value['item_nome'],
                    $value['qtd'],
                    number_format($value['item_valor'], 2, '.', '')
                );
            }

            $ddd = substr($userData['user_telefone'], 0, 2);
            $tel = substr($userData['user_telefone'], 2);

            $creditCard->setSender()->setName($userData['user_nome']);
            //$creditCard->setSender()->setEmail($userData['user_email']);
            $creditCard->setSender()->setEmail('teste@sandbox.pagseguro.com.br');

            $creditCard->setSender()->setPhone()->withParameters(
                $ddd,
                $tel
            );


            $creditCard->setSender()->setDocument()->withParameters(
                'CPF',
                $userData['user_cpf']
            );

            $creditCard->setSender()->setHash($hash);

            $creditCard->setSender()->setIp('127.0.0.0');

            $creditCard->setShipping()->setAddress()->withParameters(
                $userData['logradouro'],
                $userData['user_endereco_numero'],
                $userData['bairro'],
                $userData['user_cep'],
                $userData['cidade'],
                $userData['uf'],
                'BRA',
                $userData['user_endereco_complemento']
            );

            $creditCard->setBilling()->setAddress()->withParameters(
                $userData['logradouro'],
                $userData['user_endereco_numero'],
                $userData['bairro'],
                $userData['user_cep'],
                $userData['cidade'],
                $userData['uf'],
                'BRA',
                $userData['user_endereco_complemento']
            );

            $creditCard->setToken($token);

            $creditCard->setInstallment()->withParameters(1, $totalCompra);

            $creditCard->setHolder()->setBirthdate($userData['dateAniversario']);
            $creditCard->setHolder()->setName($userData['user_nome']); // Equals in Credit Card

            $creditCard->setHolder()->setPhone()->withParameters(
                $ddd,
                $tel
            );

            $creditCard->setHolder()->setDocument()->withParameters(
                'CPF',
                $userData['user_cpf']
            );

            $creditCard->setMode('DEFAULT');

            try {

                $result = $creditCard->register(
                    \PagSeguro\Configuration\Configure::getAccountCredentials()
                );


                $code =       $result->getCode();
                $status =     $result->getStatus();
                $bruto =      $result->getGrossAmount();
                $liquido =    $result->getNetAmount();
                $tipo_pag =   $result->getPaymentMethod()->getType();
                $taxaPag    = $result->getFeeAmount();
                $referencia = $result->getReference();

                $sql = "INSERT INTO checkout (checkout_ref, checkout_code, checkout_status, checkout_date, 
                                              checkout_last_event, checkout_valor_bruto, checkout_valor_liquido,
                                              checkout_taxa, checkout_forma_pagamento, user_id, cartao_id, filial_id, checkout_flag_id )
                VALUES ( ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?, 1);";
                $stmt = $conn->prepare($sql);

                $stmt->bindValue(1,$referencia, PDO::PARAM_STR);
                $stmt->bindValue(2,$code, PDO::PARAM_STR);
                $stmt->bindValue(3,$status, PDO::PARAM_STR);
                $stmt->bindValue(4,$bruto, PDO::PARAM_STR);
                $stmt->bindValue(5,$liquido, PDO::PARAM_STR);
                $stmt->bindValue(6,$taxaPag, PDO::PARAM_STR);
                $stmt->bindValue(7,$tipo_pag, PDO::PARAM_INT);
                $stmt->bindValue(8,$user_id, PDO::PARAM_INT);
                $stmt->bindValue(9,$cartao_id, PDO::PARAM_INT);
                $stmt->bindValue(10, $filial_id, PDO::PARAM_INT);
                $stmt->execute();
                $last_id = $conn->lastInsertId();

                foreach ($carCompleto as $key => $value) {

                    $sql = "INSERT INTO checkout_itens (checkout_item_qtd, checkout_item_valor, item_id, checkout_id)
                    VALUES ( ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);

                    $stmt->bindValue(1,$value['qtd'], PDO::PARAM_INT);
                    $stmt->bindValue(2,$value['item_valor'], PDO::PARAM_STR);
                    $stmt->bindValue(3,$value['item_id'], PDO::PARAM_INT);
                    $stmt->bindValue(4,$last_id, PDO::PARAM_INT);
                    $stmt->execute();

                }

                //Alimentar Cartão fidelidade do cliente(Quando houver)
                $sql = "SELECT  cartao_fid_id, cartao_fid_valor FROM empresa_cartao_fid
                        WHERE filial_id = ? AND cartao_fid_status = 2;";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(1,$filial_id, PDO::PARAM_INT);
                $stmt->execute();
                $countLogin = $stmt->rowCount();
                $resultFidelidade = $stmt->fetchAll(PDO::FETCH_OBJ);

                if ($countLogin !== 0 && $bruto > $resultFidelidade[0]->cartao_fid_valor) {

                    $sql = "INSERT INTO clientes_pontos_fid (cliente_ponto, user_id, checkout_id, cartao_fid_id), 
                            VALUES ( ?, ?, ?);";
                    $stmt = $conn->prepare($sql);

                    $stmt->bindValue(1,$user_id, PDO::PARAM_STR);
                    $stmt->bindValue(2,$last_id, PDO::PARAM_STR);
                    $stmt->bindValue(3,$resultFidelidade[0]->cartao_fid_id, PDO::PARAM_STR);
                    $stmt->execute();

                }

                return array(
                    'status'    => 200,
                    'message'   => "SUCCESS",
                    'code'      => $code,
                    'reference' => $referencia
                );



            } catch (Exception $e) {
                echo "</br> <strong>";
                die($e->getMessage());
            }

        }catch (Exception $e){
            echo "</br> <strong>";
            die($e->getMessage());
        }



    }

    public function notification($code, $status, $referencia, $disponivel, $lastEventDate){
        $conn = \Database::conexao();
        $sql = "UPDATE checkout SET 
                  checkout_status = ?, 
                  checkout_disponivel = ?,
                  checkout_last_event = ?

                  WHERE checkout_ref = ? 
                  AND checkout_code = ?;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$status);
            $stmt->bindValue(2,$disponivel);
            $stmt->bindValue(3,$lastEventDate);
            $stmt->bindValue(4,$referencia);
            $stmt->bindValue(5,$code);
            $stmt->execute();

            $count = $stmt->rowCount();

            if ($count == 1) {
                return $count;
            }else{
                return array(
                    'status'    => 500,
                    'message'   => "ERROR",
                    'result'    => 'Erro na execução da instrução!',
                    'linhas'    => $count
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

    public  function consult($ref, $user_id){

        $conn = \Database::conexao();
        $sql = "SELECT 
                    checkout_id,
                    checkout_status
                FROM checkout
                WHERE checkout_ref = ? 
                AND user_id = ?;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$ref);
            $stmt->bindValue(2,$user_id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            $count = $stmt->rowCount();

            if ($count != 1) {
                return array('status' => 500, 'message' => "ERROR", 'result' => 'Código e/ou usuário inválidos!');
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

    public  function appListAll($user_id){

        $conn = \Database::conexao();
        $sql = "SELECT 
                    ped.checkout_id,
                    ped.checkout_ref,
                    ped.checkout_status,
                    ped.checkout_last_event,
                    ped.checkout_date,
                    ped.checkout_date_concluido,
                    ped.checkout_date_entregue,
                    DATE_FORMAT( ped.checkout_date , '%d/%m/%Y às %H:%i:%s' ) AS checkout_date_format, 
                    DATE_FORMAT( ped.checkout_date_concluido , '%d/%m/%Y às %H:%i:%s' ) AS checkout_conc_format, 
                    DATE_FORMAT( ped.checkout_date_entregue , '%d/%m/%Y às %H:%i:%s' ) AS checkout_ent_format, 
                    ped.checkout_valor_bruto,
                    ped.user_id,
                    ped.checkout_flag_id,
                    
                    fla.checkout_flag_desc,
                    
                    fili.filial_nome,
                    empr.empresa_foto_marca 
                    
                FROM checkout ped
                INNER JOIN checkout_flag fla
                ON ped.checkout_flag_id = fla.checkout_flag_id 
                INNER JOIN empresa_filial fili
                ON ped.filial_id = fili.filial_id
                INNER JOIN empresa empr
                ON fili.empresa_id = empr.empresa_id
                WHERE ped.user_id = ?;";
        $stmt = $conn->prepare($sql);

        $sql2 = "SELECT 
                    chec.checkout_item_id,
                    chec.checkout_item_qtd,
                    chec.checkout_item_valor,
                    chec.item_id,
                    
                    item.item_nome,
                    item.item_tempo_medio

                FROM checkout_itens chec
                INNER JOIN menu_filial_itens item
                ON chec.item_id = item.item_id 
                WHERE chec.checkout_id = ?;";
        $stmt2 = $conn->prepare($sql2);

        $sql3 = "SELECT 
                    fot_id,
                    fot_file 

                FROM itens_fotos 
                WHERE item_id = ?;";
        $stmt3 = $conn->prepare($sql3);

        try {
            $stmt->bindValue(1,$user_id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = count($result);

            if ($count == 0){
                return array(
                    'status'    => 500,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Nenhum pedido encontrado!'
                );
            }else{

                foreach ($result as $key1 => $value1){
                    $stmt2->bindValue(1,$value1['checkout_id'], PDO::PARAM_INT);
                    $stmt2->execute();
                    $obj = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                    $result[$key1]['checkout_valor_bruto'] = number_format($value1['checkout_valor_bruto'], 2, '.', '');

                    foreach ($obj as $key2 => $value2) {

                        $stmt3->bindValue(1,$value2['item_id'], PDO::PARAM_INT);
                        $stmt3->execute();
                        $fotos = $stmt3->fetchAll(PDO::FETCH_ASSOC);

                        $obj[$key2]['fotos'] = $fotos;

                    }

                    $result[$key1]['itens'] = $obj;

                }

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

    public  function listAll($user_id, $filial_id, $status){

        $conn = \Database::conexao();
        $sql = "SELECT 
                    ped.checkout_id,
                    ped.checkout_ref,
                    ped.checkout_status,
                    ped.checkout_last_event,
                    ped.checkout_disponivel,
                    ped.checkout_date,
                    ped.checkout_date_concluido,
                    ped.checkout_date_entregue,
                    DATE_FORMAT( ped.checkout_date , '%d/%m/%Y às %H:%i:%s' ) AS checkout_date_format, 
                    DATE_FORMAT( ped.checkout_date_concluido , '%d/%m/%Y às %H:%i:%s' ) AS checkout_conc_format, 
                    DATE_FORMAT( ped.checkout_date_entregue , '%d/%m/%Y às %H:%i:%s' ) AS checkout_ent_format, 
                    ped.checkout_valor_bruto,
                    ped.user_id,
                    ped.user_id,
                    ped.checkout_flag_id,
                    
                    usu.user_nome,
                    usu.user_cpf,
                    usu.user_telefone,
                    usu.user_foto_perfil

                FROM checkout ped
                INNER JOIN usuarios usu
                ON ped.user_id = usu.user_id 
                WHERE ped.filial_id = ? 
                AND (ped.checkout_status = 3 OR ped.checkout_status = 4)
                AND ped.checkout_flag_id = ?;";
        $stmt = $conn->prepare($sql);

        $sql2 = "SELECT 
                    chec.checkout_item_id,
                    chec.checkout_item_qtd,
                    chec.checkout_item_valor,
                    chec.item_id,
                    
                    item.item_nome,
                    item.item_tempo_medio

                FROM checkout_itens chec
                INNER JOIN menu_filial_itens item
                ON chec.item_id = item.item_id 
                WHERE chec.checkout_id = ?;";
        $stmt2 = $conn->prepare($sql2);

        $sql3 = "SELECT 
                    fot_id,
                    fot_file 

                FROM itens_fotos 
                WHERE item_id = ?;";
        $stmt3 = $conn->prepare($sql3);

        try {
            $stmt->bindValue(1,$filial_id);
            $stmt->bindValue(2,$status);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = count($result);

            if ($count == 0){
                return array(
                    'status'    => 500,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Nenhum pedido encontrado!'
                );
            }else{

                foreach ($result as $key1 => $value1){
                    $stmt2->bindValue(1,$value1['checkout_id'], PDO::PARAM_INT);
                    $stmt2->execute();
                    $obj = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                    $result[$key1]['checkout_valor_bruto'] = number_format($value1['checkout_valor_bruto'], 2, '.', '');

                    foreach ($obj as $key2 => $value2) {

                        $stmt3->bindValue(1,$value2['item_id'], PDO::PARAM_INT);
                        $stmt3->execute();
                        $fotos = $stmt3->fetchAll(PDO::FETCH_ASSOC);

                        $obj[$key2]['fotos'] = $fotos;

                    }

                    $result[$key1]['itens'] = $obj;

                }

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

    public function changeFlag($status, $idChange){

        $conn = \Database::conexao();

        $collum = ($status == 2)? "checkout_date_concluido" : "checkout_date_entregue";

        $sql = "SELECT 
                    chec.checkout_ref,
                    chec.checkout_date_concluido,
                    chec.user_id,
                    usu.user_nome
                    
                FROM checkout chec
                INNER JOIN usuarios usu
                ON usu.user_id = chec.user_id
                WHERE chec.checkout_id = ?;";
        $stmt = $conn->prepare($sql);

        $sql2 = "UPDATE checkout
                SET  ".$collum."  = NOW(),
                checkout_flag_id = ?
                WHERE checkout_id = ?";
        $stmt2 = $conn->prepare($sql2);

        try {
            $stmt->bindValue(1,$idChange);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $ref = substr($result[0]["checkout_ref"], -6);
            $nome = explode(" ", $result[0]["user_nome"]);

            $server_key = 'AAAAHTO1V3s:APA91bE-n76NJl5OfMx9D7i44xkbiswnKN1zzOv8iAFOidjimuD6uPu1opNnOrgPLti1_K-MCLJFPOvbDXmwOPYeezIDYxczwkgjEgW5HAEqzue5yLtvX_UFl_IGZEn8AzUFQN-Nzd2_';
            $client = new Client();
            $client->setApiKey($server_key);
            $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

            $notification = new Notification();

            switch ($status){
                case 2:
                    $notification->setTitle("Acabou a espera!");
                    $notification->setBody("Olá ".$nome[0]."! Seu pedido #".$ref." já está pronto! Por favor, retire no balcão.");
                    $notification->setIcon("notification_icon");
                    $notification->setSound('default');

                    $array = array(
                        'title' => 'Acabou a espera!',
                        'body'  => "Olá ".$nome[0]."! Seu pedido #".$ref." já está pronto! Por favor, retire no balcão.",
                        'key'   => 2,
                        'ref'   => $ref,
                        'icon'  => 'notification_icon',
                        'sound' => 'default'
                    );
                    break;

                case 3:
                    $notification->setTitle("Agradecemos sua visita!");
                    $notification->setBody($nome[0].", muito obrigado por utilizar o I`m Hungry!");
                    $notification->setIcon("notification_icon");
                    $notification->setSound('default');

                    $array = array(
                        'title' => "Agradecemos sua visita!",
                        'body'  => $nome[0].", muito obrigado por utilizar o I`m Hungry!",
                        'key'   => 3,
                        'ref'   => $ref,
                        'icon'  => 'notification_icon',
                        'sound' => 'default'
                    );
                    break;

            }

            $message = new Message();
            $message->setPriority('high');
            $message->addRecipient(new Topic('com.br.ImHungryApp-'.$result[0]["user_id"]));
            $message
                ->setNotification($notification)
                ->setData($array)
            ;

            $response = $client->send($message);

            if ($response->getStatusCode() === 200){

                $return = json_decode($response->getBody()->getContents());
                $idNotification = $return->message_id;

                $stmt2->bindValue(1,$status);
                $stmt2->bindValue(2,$idChange);
                $stmt2->execute();

                return array(
                    'status'    => 200,
                    'message'   => "SUCCESS"
                );

            }else{

                return array(
                    'status'    => 500,
                    'message'   => "ERROR",
                    'result'   => "Notificação não enviada! Tente novamente."
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