<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 05/03/18
 * Time: 08:08
 */

class Enderecos
{
    private $id;
    private $cep;
    private $uf;
    private $cidade;
    private $bairro;
    private $logradouro;
    private $latitude;
    private $longitude;
    private $ibge_cod_uf;
    private $ibge_cod_cidade;
    private $area_cidade_km2;
    private $ddd;

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
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * @param mixed $cep
     */
    public function setCep($cep)
    {
        $this->cep = $cep;
    }

    /**
     * @return mixed
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @param mixed $uf
     */
    public function setUf($uf)
    {
        $this->uf = $uf;
    }

    /**
     * @return mixed
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * @param mixed $cidade
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    /**
     * @return mixed
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * @param mixed $bairro
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

    /**
     * @return mixed
     */
    public function getLogradouro()
    {
        return $this->logradouro;
    }

    /**
     * @param mixed $logradouro
     */
    public function setLogradouro($logradouro)
    {
        $this->logradouro = $logradouro;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getIbgeCodUf()
    {
        return $this->ibge_cod_uf;
    }

    /**
     * @param mixed $ibge_cod_uf
     */
    public function setIbgeCodUf($ibge_cod_uf)
    {
        $this->ibge_cod_uf = $ibge_cod_uf;
    }

    /**
     * @return mixed
     */
    public function getIbgeCodCidade()
    {
        return $this->ibge_cod_cidade;
    }

    /**
     * @param mixed $ibge_cod_cidade
     */
    public function setIbgeCodCidade($ibge_cod_cidade)
    {
        $this->ibge_cod_cidade = $ibge_cod_cidade;
    }

    /**
     * @return mixed
     */
    public function getAreaCidadeKm2()
    {
        return $this->area_cidade_km2;
    }

    /**
     * @param mixed $area_cidade_km2
     */
    public function setAreaCidadeKm2($area_cidade_km2)
    {
        $this->area_cidade_km2 = $area_cidade_km2;
    }

    /**
     * @return mixed
     */
    public function getDdd()
    {
        return $this->ddd;
    }

    /**
     * @param mixed $ddd
     */
    public function setDdd($ddd)
    {
        $this->ddd = $ddd;
    }



}