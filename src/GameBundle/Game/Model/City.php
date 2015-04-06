<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 4/6/2015
 * Time: 11:50 AM
 */

namespace GameBundle\Game\Model;

use GameBundle\Game\DBCommon;

class City extends GameEntity
{
    protected $named;
    protected $imgsmall;
    protected $description;
    protected $depot;
    protected $king;
    protected $priest;
    protected $x;
    protected $y;

    /**
     * @return mixed
     */
    public function getNamed()
    {
        return $this->named;
    }

    /**
     * @param mixed $named
     */
    public function setNamed($named)
    {
        $this->named = $named;
    }

    /**
     * @return mixed
     */
    public function getImgsmall()
    {
        return $this->imgsmall;
    }

    /**
     * @param mixed $imgsmall
     */
    public function setImgsmall($imgsmall)
    {
        $this->imgsmall = $imgsmall;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDepot()
    {
        return $this->depot;
    }

    /**
     * @param mixed $depot
     */
    public function setDepot($depot)
    {
        $this->depot = $depot;
    }

    /**
     * @return mixed
     */
    public function getKing()
    {
        return $this->king;
    }

    /**
     * @param mixed $king
     */
    public function setKing($king)
    {
        $this->king = $king;
    }

    /**
     * @return mixed
     */
    public function getPriest()
    {
        return $this->priest;
    }

    /**
     * @param mixed $priest
     */
    public function setPriest($priest)
    {
        $this->priest = $priest;
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return DBCommon
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param DBCommon $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

}