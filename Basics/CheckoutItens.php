<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 05/03/18
 * Time: 07:58
 */

class CheckoutItens
{
    private $id;
    private $qtd;
    private $valor;
    private $item_id;
    private $checkout_id;

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
    public function getQtd()
    {
        return $this->qtd;
    }

    /**
     * @param mixed $qtd
     */
    public function setQtd($qtd)
    {
        $this->qtd = $qtd;
    }

    /**
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param mixed $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @return mixed
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * @param mixed $item_id
     */
    public function setItemId($item_id)
    {
        $this->item_id = $item_id;
    }

    /**
     * @return mixed
     */
    public function getCheckoutId()
    {
        return $this->checkout_id;
    }

    /**
     * @param mixed $checkout_id
     */
    public function setCheckoutId($checkout_id)
    {
        $this->checkout_id = $checkout_id;
    }



}