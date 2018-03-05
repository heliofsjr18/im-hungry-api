<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 05/03/18
 * Time: 07:55
 */

class Checkout
{
    private $id;
    private $ref;
    private $code;
    private $date;
    private $lastEvent;
    private $valorBruto;
    private $valorLiquido;
    private $formaPagamento;
    private $user_id;
    private $cupom_id;
    private $cartao_id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * @param mixed $ref
     */
    public function setRef($ref)
    {
        $this->ref = $ref;
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

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getLastEvent()
    {
        return $this->lastEvent;
    }

    /**
     * @param mixed $lastEvent
     */
    public function setLastEvent($lastEvent)
    {
        $this->lastEvent = $lastEvent;
    }

    /**
     * @return mixed
     */
    public function getValorBruto()
    {
        return $this->valorBruto;
    }

    /**
     * @param mixed $valorBruto
     */
    public function setValorBruto($valorBruto)
    {
        $this->valorBruto = $valorBruto;
    }

    /**
     * @return mixed
     */
    public function getValorLiquido()
    {
        return $this->valorLiquido;
    }

    /**
     * @param mixed $valorLiquido
     */
    public function setValorLiquido($valorLiquido)
    {
        $this->valorLiquido = $valorLiquido;
    }

    /**
     * @return mixed
     */
    public function getFormaPagamento()
    {
        return $this->formaPagamento;
    }

    /**
     * @param mixed $formaPagamento
     */
    public function setFormaPagamento($formaPagamento)
    {
        $this->formaPagamento = $formaPagamento;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getCupomId()
    {
        return $this->cupom_id;
    }

    /**
     * @param mixed $cupom_id
     */
    public function setCupomId($cupom_id)
    {
        $this->cupom_id = $cupom_id;
    }

    /**
     * @return mixed
     */
    public function getCartaoId()
    {
        return $this->cartao_id;
    }

    /**
     * @param mixed $cartao_id
     */
    public function setCartaoId($cartao_id)
    {
        $this->cartao_id = $cartao_id;
    }



}