<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 20/03/18
 * Time: 07:13
 */

require_once 'Connection/Conexao.php';
require_once "lib/vendor/autoload.php";
class CheckoutItensDAO
{
    public function generate($array_itens, $array_qtd, $user_id){

        $conn = \Database::conexao();
        $sql = "SELECT item_id, item_nome, item_valor
                FROM menu_filial_itens 
                WHERE item_id = ?;";
        $stmt = $conn->prepare($sql);

        $sql2 = "SELECT user_id, user_nome, user_cpf, user_email, user_telefone,
                        user_data, user_cadastro, user_foto_perfil, user_status, tipo_id,
						DATE_FORMAT(user_data, '%d/%m/%Y') as dateAniversario, 
						DATE_FORMAT(user_cadastro, '%d/%m/%Y') as dateCadatro 
			FROM usuarios WHERE user_id = ? LIMIT 1;";
        $stmt2 = $conn->prepare($sql2);

        try {
            $stmt2->bindValue(1,$user_id);
            $stmt2->execute();
            $userData = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            $carCompleto = [];

            foreach ($array_itens as $key => $value) {

                $stmt->bindValue(1,$value, PDO::PARAM_INT);
                $stmt->execute();
                $itemCompleto = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $itemCompxleto[0]['qtd'] = $array_qtd[$key];

                array_push($carCompleto, $itemCompleto[0]);
            }

            //Chamada PagSeguro

            \PagSeguro\Library::initialize();
            \PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
            \PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

            $creditCard = new \PagSeguro\Domains\Requests\DirectPayment\CreditCard();
            $ref = strtoupper(uniqid());

            $creditCard->setReceiverEmail('contato@rafafreitas.com');
            $creditCard->setReference($ref);
            $creditCard->setCurrency("BRL");

            foreach ($carCompleto as $key => $value) {

                $aux = $key + 1;
                $creditCard->addItems()->withParameters(
                    $aux,
                    $value['item_nome'],
                    $value['qtd'],
                    $value['item_valor']
                );
            }

            //Set billing information for credit card
            $creditCard->setBilling()->setAddress()->withParameters(
                'Av. Brig. Faria Lima',
                '1384',
                'Jardim Paulistano',
                '01452002',
                'São Paulo',
                'SP',
                'BRA',
                'apto. 114'
            );

            // Set credit card token
            $creditCard->setToken('c147bd085c8e495f8b89f5c28a0386a1');

            // Set the installment quantity and value (could be obtained using the Installments
            // service, that have an example here in \public\getInstallments.php)
            $creditCard->setInstallment()->withParameters(1, '30.00');

            // Set the credit card holder information
            $creditCard->setHolder()->setBirthdate('01/10/1979');
            $creditCard->setHolder()->setName($userData['user_nome']); // Equals in Credit Card

            $creditCard->setHolder()->setPhone()->withParameters(
                11,
                56273440
            );

            $creditCard->setHolder()->setDocument()->withParameters(
                'CPF',
                $userData['user_cpf']
            );

            // Set the Payment Mode for this payment request
            $creditCard->setMode('DEFAULT');

            // Set a reference code for this payment request. It is useful to identify this payment
            // in future notifications.


            try {
                //Get the crendentials and register the boleto payment
                $result = $creditCard->register(
                    \PagSeguro\Configuration\Configure::getAccountCredentials()
                );
                echo "<pre>";
                print_r($result);
            } catch (Exception $e) {
                echo "</br> <strong>";
                die($e->getMessage());
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

//        \PagSeguro\Library::initialize();
//        \PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
//        \PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");
//        \PagSeguro\Configuration\Configure::setCharset('UTF-8');// UTF-8 or ISO-8859-1
//        //\PagSeguro\Configuration\Configure::setLog(true, '/logpath/pagseguro.log');
//
//        \PagSeguro\Configuration\Configure::setEnvironment('sandbox');//production or sandbox
//        \PagSeguro\Configuration\Configure::setAccountCredentials(
//            /*E-Mail*/
//            'pagseguro@rafafreitas.com',
//
//            /*sandbox*/
//            'E6D827F59A0A46488AB467A4BDB4A43E'
//
//            /*Produção*/
//            //'0C72863036CA4D8E9B7486AC70BA8DE7'
//        );