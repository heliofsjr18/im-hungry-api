<?php

class FidelidadeFilial
{
    private $id;
    private $qtd;
    private $valor;
    private $beneficio;
    private $filial_id;

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
    public function getBeneficio()
    {
        return $this->beneficio;
    }

    /**
     * @param mixed $beneficio
     */
    public function setBeneficio($beneficio)
    {
        $this->beneficio = $beneficio;
    }

    /**
     * @return mixed
     */
    public function getFilialId()
    {
        return $this->filial_id;
    }

    /**
     * @param mixed $filial_id
     */
    public function setFilialId($filial_id)
    {
        $this->filial_id = $filial_id;
    }

}