<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 01/04/18
 * Time: 10:37
 */

class StatusPagSeguro
{
    private $code;

    public function getStaus(){
        switch ($this->getCode()){
            case 1:
                return array(
                    'code'          => 1,
                    'significado'   => "Aguardando pagamento",
                    'explicacao'    => "O comprador iniciou a transação, mas até o momento o PagSeguro não recebeu nenhuma informação sobre o pagamento."
                );
                break;

            case 2:
                return array(
                    'code'          => 2,
                    'significado'   => "Em análise",
                    'explicacao'    => "O comprador optou por pagar com um cartão de crédito e o PagSeguro está analisando o risco da transação."
                );
                break;

            case 3:
                return array(
                    'code'          => 3,
                    'significado'   => "Paga",
                    'explicacao'    => "A transação foi paga pelo comprador e o PagSeguro já recebeu uma confirmação da instituição financeira responsável pelo processamento."
                );
                break;

            case 4:
                return array(
                    'code'          => 4,
                    'significado'   => "Disponível",
                    'explicacao'    => "A transação foi paga e chegou ao final de seu prazo de liberação sem ter sido retornada e sem que haja nenhuma disputa aberta."
                );
                break;

            case 5:
                return array(
                    'code'          => 5,
                    'significado'   => "Em disputa",
                    'explicacao'    => "A comprador, dentro do prazo de liberação da transação, abriu uma disputa."
                );
                break;

            case 6:
                return array(
                    'code'          => 6,
                    'significado'   => "Devolvida",
                    'explicacao'    => "A valor da transação foi devolvido para o comprador."
                );
                break;

            case 7:
                return array(
                    'code'          => 7,
                    'significado'   => "Cancelada",
                    'explicacao'    => "A transação foi cancelada sem ter sido finalizada."
                );
                break;
        }
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
}