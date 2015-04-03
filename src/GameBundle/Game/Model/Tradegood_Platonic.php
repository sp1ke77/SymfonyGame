<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 4/3/2015
 * Time: 2:09 PM
 */

namespace GameBundle\Game\Model;

class Tradegood_Platonic extends GameEntity
{

    protected $named;
    protected $imgfull;
    protected $description;
    protected $tradevalue;
    protected $foodvalue;
    protected $tgtype;

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
    public function getImgfull()
    {
        return $this->imgfull;
    }

    /**
     * @param mixed $imgfull
     */
    public function setImgfull($imgfull)
    {
        $this->imgfull = $imgfull;
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
    public function getTradevalue()
    {
        return $this->tradevalue;
    }

    /**
     * @param mixed $tradevalue
     */
    public function setTradevalue($tradevalue)
    {
        $this->tradevalue = $tradevalue;
    }

    /**
     * @return mixed
     */
    public function getFoodvalue()
    {
        return $this->foodvalue;
    }

    /**
     * @param mixed $foodvalue
     */
    public function setFoodvalue($foodvalue)
    {
        $this->foodvalue = $foodvalue;
    }

    /**
     * @return mixed
     */
    public function getTgtype()
    {
        return $this->tgtype;
    }

    /**
     * @param mixed $tgtype
     */
    public function setTgtype($tgtype)
    {
        $this->tgtype = $tgtype;
    }
}