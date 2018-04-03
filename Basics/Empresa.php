<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 05/03/18
 * Time: 08:10
 */

class Empresa
{
    private $id;
    private $nome;
    private $telefone;
    private $cnpj;
    private $cep;
    private $latitude;
    private $longitude;
    private $numero_endereco;
    private $complemento_endereco;
    private $data_fundacao;
    private $data_cadastro;
    private $foto_marca;
    private $foto_perfil;
    private $foto_capa;
    private $facebook;
    private $instagram;
    private $twitter;
    private $status;
    private $enabled;
    private $user_id;

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
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * @param mixed $telefone
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    /**
     * @return mixed
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * @param mixed $cnpj
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
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
    public function getNumeroEndereco()
    {
        return $this->numero_endereco;
    }

    /**
     * @param mixed $numero_endereco
     */
    public function setNumeroEndereco($numero_endereco)
    {
        $this->numero_endereco = $numero_endereco;
    }

    /**
     * @return mixed
     */
    public function getComplementoEndereco()
    {
        return $this->complemento_endereco;
    }

    /**
     * @param mixed $complemento_endereco
     */
    public function setComplementoEndereco($complemento_endereco)
    {
        $this->complemento_endereco = $complemento_endereco;
    }

    /**
     * @return mixed
     */
    public function getDataFundacao()
    {
        return $this->data_fundacao;
    }

    /**
     * @param mixed $data_fundacao
     */
    public function setDataFundacao($data_fundacao)
    {
        $this->data_fundacao = $data_fundacao;
    }

    /**
     * @return mixed
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * @param mixed $data_cadastro
     */
    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
    }

    /**
     * @return mixed
     */
    public function getFotoMarca()
    {
        return $this->foto_marca;
    }

    /**
     * @param mixed $foto_marca
     */
    public function setFotoMarca($foto_marca)
    {
        $this->foto_marca = $foto_marca;
    }

    /**
     * @return mixed
     */
    public function getFotoPerfil()
    {
        return $this->foto_perfil;
    }

    /**
     * @param mixed $foto_perfil
     */
    public function setFotoPerfil($foto_perfil)
    {
        $this->foto_perfil = $foto_perfil;
    }

    /**
     * @return mixed
     */
    public function getFotoCapa()
    {
        return $this->foto_capa;
    }

    /**
     * @param mixed $foto_capa
     */
    public function setFotoCapa($foto_capa)
    {
        $this->foto_capa = $foto_capa;
    }

    /**
     * @return mixed
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param mixed $facebook
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @return mixed
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * @param mixed $instagram
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;
    }

    /**
     * @return mixed
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param mixed $twitter
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
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
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
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

}