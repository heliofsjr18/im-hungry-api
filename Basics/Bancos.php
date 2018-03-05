<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 05/03/18
 * Time: 08:03
 */

class Bancos
{
    private $banco_id;
    private $cod;
    private $banco;

    /**
     * @return mixed
     */
    public function getBancoId()
    {
        return $this->banco_id;
    }

    /**
     * @param mixed $banco_id
     */
    public function setBancoId($banco_id)
    {
        $this->banco_id = $banco_id;
    }

    /**
     * @return mixed
     */
    public function getCod()
    {
        return $this->cod;
    }

    /**
     * @param mixed $cod
     */
    public function setCod($cod)
    {
        $this->cod = $cod;
    }

    /**
     * @return mixed
     */
    public function getBanco()
    {
        return $this->banco;
    }

    /**
     * @param mixed $banco
     */
    public function setBanco($banco)
    {
        $this->banco = $banco;
    }


}