<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 05/03/18
 * Time: 08:04
 */

class BancoEmpresa
{
    private $id;
    private $titular_nome;
    private $titular_tipo;
    private $titular_doc;
    private $agencia;
    private $conta;
    private $flag;
    private $banco_id;
    private $empresa_id;

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
    public function getTitularNome()
    {
        return $this->titular_nome;
    }

    /**
     * @param mixed $titular_nome
     */
    public function setTitularNome($titular_nome)
    {
        $this->titular_nome = $titular_nome;
    }

    /**
     * @return mixed
     */
    public function getTitularTipo()
    {
        return $this->titular_tipo;
    }

    /**
     * @param mixed $titular_tipo
     */
    public function setTitularTipo($titular_tipo)
    {
        $this->titular_tipo = $titular_tipo;
    }

    /**
     * @return mixed
     */
    public function getTitularDoc()
    {
        return $this->titular_doc;
    }

    /**
     * @param mixed $titular_doc
     */
    public function setTitularDoc($titular_doc)
    {
        $this->titular_doc = $titular_doc;
    }

    /**
     * @return mixed
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * @param mixed $agencia
     */
    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;
    }

    /**
     * @return mixed
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * @param mixed $conta
     */
    public function setConta($conta)
    {
        $this->conta = $conta;
    }

    /**
     * @return mixed
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @param mixed $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

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
    public function getEmpresaId()
    {
        return $this->empresa_id;
    }

    /**
     * @param mixed $empresa_id
     */
    public function setEmpresaId($empresa_id)
    {
        $this->empresa_id = $empresa_id;
    }


}