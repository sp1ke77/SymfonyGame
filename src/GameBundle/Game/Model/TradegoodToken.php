<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 4/3/2015
 * Time: 2:04 PM
 */

namespace GameBundle\Game\Model;

class TradegoodToken extends GameEntity
{
    protected $id;
    protected $mapzone;
    protected $tg;
    protected $named;
    protected $tradevalue;
    protected $foodvalue;

    /**
     * @return mixed
     */
    public function getMapzone()
    {
        return $this->mapzone;
    }

    /**
     * @param mixed $mapzone
     */
    public function setMapzone($mapzone)
    {
        $this->mapzone = $mapzone;
    }

    /**
     * @return mixed
     */
    public function getTg()
    {
        return $this->tg;
    }

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
     * @param mixed $tg
     */
    public function setTg($tg)
    {
        $this->tg = $tg;
    }

    public function hydratePlatonicProperties()
    {
        $query = 'SELECT * FROM tradegoodplatonic WHERE id=' .$this->id. ';';
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObject();

        $this->named = $loadObj->named;
        $this->foodvalue = $loadObj->foodvalue;
        $this->tradevalue = $loadObj->tradevalue;
    }
}