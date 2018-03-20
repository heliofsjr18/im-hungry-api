<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 05/03/18
 * Time: 08:33
 */

class MenuFilialItens
{
    private $id;
    private $nome;
    private $valor;
    private $tempo_medio;
    private $status;
    private $promocao;
    private $filial_id;

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
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
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
    public function getTempoMedio()
    {
        return $this->tempo_medio;
    }

    /**
     * @param mixed $tempo_medio
     */
    public function setTempoMedio($tempo_medio)
    {
        $this->tempo_medio = $tempo_medio;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getPromocao()
    {
        return $this->promocao;
    }

    /**
     * @param mixed $promocao
     */
    public function setPromocao($promocao)
    {
        $this->promocao = $promocao;
    }




}