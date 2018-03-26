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
    public function generate($array_itens, $array_qtd, $token, $hash, $user_id){

        $conn = \Database::conexao();
        $sql = "SELECT item_id, item_nome, item_valor
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
						ende.cidade
						
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

            foreach ($array_itens as $key => $value) {

                $stmt->bindValue(1,$value, PDO::PARAM_INT);
                $stmt->execute();
                $itemCompleto = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $itemCompleto[0]['qtd'] = $array_qtd[$key];
                $totalCompra += ($itemCompleto[0]['item_valor'] * $array_qtd[$key]);
                array_push($carCompleto, $itemCompleto[0]);
            }

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

                var_dump($result);
                die;

            } catch (Exception $e) {
                echo "</br> <strong>";
                die($e->getMessage());
            }

        }catch (Exception $e){
            echo "</br> <strong>";
            die($e->getMessage());
        }



    }
}