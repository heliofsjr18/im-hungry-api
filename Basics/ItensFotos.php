<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 05/03/18
 * Time: 08:35
 */

class ItensFotos
{

    private $id;
    private $fot_file;
    private $item_id;

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
    public function getFotFile()
    {
        return $this->fot_file;
    }

    /**
     * @param mixed $fot_file
     */
    public function setFotFile($fot_file)
    {
        $this->fot_file = $fot_file;
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



}