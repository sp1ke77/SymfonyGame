<?php

namespace GameBundle\Game\Model;

use GameBundle\Game\DBCommon;
use GameBundle\Game\Rules\Interfaces\IDepotHaver;

class Estate extends GameEntity implements IDepotHaver
{
    // This is a bank of booleans, basically, represented in the DB as integers.
    // A value of 1 means that the building is present.

    protected $depot;           // Buy and sell tradegoods
    protected $field;           // Create food goods
    protected $quay;            // Trade range increased, chance of trade opportunity
    protected $caravansary;     // Same as "Caravansary", bonuses stack
    protected $garden;          // Improved diplomacy with clans
    protected $beerhouse;       // Recruit mercanaries
    protected $barque;          // Improved prestige gain
    protected $clanhome;        // Field a clan unit
    protected $shrine;          // Can buy temporary protection against honor loss
    protected $palace;          // Field an army unit

    // Necessary for implementing trade interface
    protected $coin;            // Current bartering power

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
    public function getQuay()
    {
        return $this->quay;
    }

    /**
     * @param mixed $quay
     */
    public function setQuay($quay)
    {
        $this->quay = $quay;
    }

    /**
     * @return mixed
     */
    public function getCaravansary()
    {
        return $this->caravansary;
    }

    /**
     * @param mixed $caravansary
     */
    public function setCaravansary($caravansary)
    {
        $this->caravansary = $caravansary;
    }

    /**
     * @return mixed
     */
    public function getGarden()
    {
        return $this->garden;
    }

    /**
     * @param mixed $garden
     */
    public function setGarden($garden)
    {
        $this->garden = $garden;
    }

    /**
     * @return mixed
     */
    public function getBeerhouse()
    {
        return $this->beerhouse;
    }

    /**
     * @param mixed $beerhouse
     */
    public function setBeerhouse($beerhouse)
    {
        $this->beerhouse = $beerhouse;
    }

    /**
     * @return mixed
     */
    public function getBarque()
    {
        return $this->barque;
    }

    /**
     * @param mixed $barque
     */
    public function setBarque($barque)
    {
        $this->barque = $barque;
    }

    /**
     * @return mixed
     */
    public function getClanhome()
    {
        return $this->clanhome;
    }

    /**
     * @param mixed $clanhome
     */
    public function setClanhome($clanhome)
    {
        $this->clanhome = $clanhome;
    }

    /**
     * @return mixed
     */
    public function getShrine()
    {
        return $this->shrine;
    }

    /**
     * @param mixed $shrine
     */
    public function setShrine($shrine)
    {
        $this->shrine = $shrine;
    }

    /**
     * @return mixed
     */
    public function getPalace()
    {
        return $this->palace;
    }

    /**
     * @param mixed $palace
     */
    public function setPalace($palace)
    {
        $this->palace = $palace;
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
}