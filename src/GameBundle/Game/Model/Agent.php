<?php

namespace GameBundle\Game\Model;

use GameBundle\Game\DBCommon;
use GameBundle\Game\Rules\Interfaces\IMappable;

class Agent extends GameEntity implements IMappable
{
    /**
     * @var
     */
    protected $isplayer;
    protected $ptype;
    protected $userid;
    protected $activity;
    protected $x, $y;
    protected $named;
    protected $culture;
    protected $city;
    protected $tradeincome;
    protected $coin;
    protected $holdings;
    protected $persona;
    protected $allegiance;

    /**
     * @return mixed
     */
    public function getTradeincome()
    {
        return $this->tradeincome;
    }

    /**
     * @param mixed $tradeincome
     */
    public function setTradeincome($tradeincome)
    {
        $this->tradeincome = $tradeincome;
    }

    /**
     * @return mixed
     */
    public function getCoin()
    {
        return $this->coin;
    }

    /**
     * @param mixed $coin
     */
    public function setCoin($coin)
    {
        $this->coin = $coin;
    }

    /**
     * @return mixed
     */
    public function getPtype()
    {
        return $this->ptype;
    }

    /**
     * @param mixed $ptype
     */
    public function setPtype($ptype)
    {
        $this->ptype = $ptype;
    }

    /**
     * @return mixed
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @param mixed $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     * @return mixed
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param mixed $activity
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;
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
    public function getCulture()
    {
        return $this->culture;
    }

    /**
     * @param mixed $culture
     */
    public function setCulture($culture)
    {
        $this->culture = $culture;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getHoldings()
    {
        return $this->holdings;
    }

    /**
     * @param mixed $holdings
     */
    public function setHoldings($holdings)
    {
        $this->holdings = $holdings;
    }

    /**
     * @return mixed
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * @param mixed $persona
     */
    public function setPersona($persona)
    {
        $this->persona = $persona;
    }

    /**
     * @return mixed
     */
    public function getAllegiance()
    {
        return $this->allegiance;
    }

    /**
     * @param mixed $allegiance
     */
    public function setAllegiance($allegiance)
    {
        $this->allegiance = $allegiance;
    }

    /**
     * @return mixed
     */
    public function getIsplayer()
    {
        return $this->isplayer;
    }

    /**
     * @param mixed $isplayer
     */
    public function setIsplayer($isplayer)
    {
        $this->isplayer = $isplayer;
    }

}