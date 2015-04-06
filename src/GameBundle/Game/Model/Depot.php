<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/25/2015
 * Time: 1:03 PM
 */
namespace GameBundle\Game\Model;

use GameBundle\Game\DBCommon;
use ReflectionClass;

class Depot extends GameEntity
{
    protected $wheat;
    protected $olives;
    protected $cattle;
    protected $fish;
    protected $copper;
    protected $incense;
    protected $gold;
    protected $wood;
    protected $linen;
    protected $dyes;

    /**
     * @return mixed
     */
    public function getWheat()
    {
        return $this->wheat;
    }

    /**
     * @param mixed $wheat
     */
    public function setWheat($wheat)
    {
        $this->wheat = $wheat;
    }

    /**
     * @return mixed
     */
    public function getOlives()
    {
        return $this->olives;
    }

    /**
     * @param mixed $olives
     */
    public function setOlives($olives)
    {
        $this->olives = $olives;
    }

    /**
     * @return mixed
     */
    public function getCattle()
    {
        return $this->cattle;
    }

    /**
     * @param mixed $cattle
     */
    public function setCattle($cattle)
    {
        $this->cattle = $cattle;
    }

    /**
     * @return mixed
     */
    public function getFish()
    {
        return $this->fish;
    }

    /**
     * @param mixed $fish
     */
    public function setFish($fish)
    {
        $this->fish = $fish;
    }

    /**
     * @return mixed
     */
    public function getCopper()
    {
        return $this->copper;
    }

    /**
     * @param mixed $copper
     */
    public function setCopper($copper)
    {
        $this->copper = $copper;
    }

    /**
     * @return mixed
     */
    public function getIncense()
    {
        return $this->incense;
    }

    /**
     * @param mixed $incense
     */
    public function setIncense($incense)
    {
        $this->incense = $incense;
    }

    /**
     * @return mixed
     */
    public function getGold()
    {
        return $this->gold;
    }

    /**
     * @param mixed $gold
     */
    public function setGold($gold)
    {
        $this->gold = $gold;
    }

    /**
     * @return mixed
     */
    public function getWood()
    {
        return $this->wood;
    }

    /**
     * @param mixed $wood
     */
    public function setWood($wood)
    {
        $this->wood = $wood;
    }

    /**
     * @return mixed
     */
    public function getLinen()
    {
        return $this->linen;
    }

    /**
     * @param mixed $linen
     */
    public function setLinen($linen)
    {
        $this->linen = $linen;
    }

    /**
     * @return mixed
     */
    public function getDyes()
    {
        return $this->dyes;
    }

    /**
     * @param mixed $dyes
     */
    public function setDyes($dyes)
    {
        $this->dyes = $dyes;
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

    /*
     *
     *
     *          DEPOT SPECIFIC FUNCTIONS
     *
     *
     */

    /**
     * Get a representation of one of the platonic tradegood categories. Must be spelled correctly.
     * Use this if you need the text description, images or current tradevalue/foodvalue properties.
     *
     * @param $fieldname
     * @return null|\stdClass
     */
    public function GetPlatonic($fieldname)
    {
        $query = "SELECT * FROM tradegood_platonic WHERE named='" . strtolower($fieldname) . "';";
        $this->db->setDb($query);
        $this->db->query();
        return $this->db->loadObject();
    }

    /*
     *
     *
     *
     */
    public function Buy($good, $amt, $coin) {

        $reflection = New ReflectionClass($this);
        if ($coin > ($amt * $this->GetPlatonic($good)->tradevalue))
        {
            $reflection->{$good} += $amt;
            $this->update();
            return true;
        } else {
            return false;
        }
    }

    public function Sell($good, $amt)
    {
        $reflection = New ReflectionClass($this);
        if ($reflection->{$good} > $amt)
        {
            $reflection->{$good} -= $amt;
            $this->update();
            return ($amt * $this->getPlatonic($good)->tradevalue);
        } else {
            return 0;
        }
    }

    /** @param $good string */
    public function Produce($good) {
        $reflection = New ReflectionClass($this);
        $properties = $reflection->getProperties();

        foreach ($properties as $node) {
            $propertyName = $node->getName();
            if ($propertyName == $good) {
                $query = 'UPDATE depot SET ' .$propertyName. '=' .($this->{$propertyName} + 1). ' WHERE id=' .$this->getId() . ';';
                $this->db->setQuery($query);
                $this->db->query();
            }
        }
    }

    /**
     * @param $good string
     * @return int */
    public function CheckOne($good)
    {
        $reflection = New ReflectionClass($this);
        $properties = $reflection->getProperties();

        foreach ($properties as $node) {
            $propertyName = $node->getName();
            if ($propertyName == $good) {
                $n = $this->{$propertyName};
                return $n;
            }
        }
    }

    public function sellAllNonfood() {
        $total = 0;
        $total += $this->Sell('wood', 5);
        $total += $this->Sell('incense', 5);
        $total += $this->Sell('gold', 5);
        $total += $this->Sell('copper', 5);
        $total += $this->Sell('dyes', 5);
        $total += $this->Sell('linen', 5);
        return $total;
    }

    public function buyAllFood()
    {
        $query = 'UPDATE depot SET wheat=' .($this->wheat + 10). ' WHERE id=' .$this->getId();
        $this->db->setQuery($query);
        $this->db->query();
    }
}