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
    public $wheat;
    public $olives;
    public $cattle;
    public $fish;
    public $copper;
    public $incense;
    public $gold;
    public $wood;
    public $linen;
    public $dyes;

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
     * @return TradegoodPlatonic
     */
    public function GetPlatonic($fieldname)
    {
        $query = "SELECT id FROM tradegoodplatonic WHERE named='" . strtolower($fieldname) . "';";
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObject();
        $tgp = new TradegoodPlatonic($loadObj->id);
        $tgp->setDb($this->db);
        $tgp->load();
        return $tgp;
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

        $this->update();
    }

    /**
     * @param $good string
     * @return int
     */
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

    /** @return Array */
    public function Assess()
    {
        $reflection = New ReflectionClass($this);
        $properties = $reflection->getProperties();
        // Get all the Tradegoods from the database
        $output = [];
        foreach ($properties as $node) {
            if (($node->getName() != 'id' && $node->getName() != 'db') && ($this->{$node->getName()} > 0))
            {
                $tgp = $this->getPlatonic($node->getName());
                unset($tgp->db);
                if ($tgp->getTgtype() == 'food') { array_push($output, $tgp); }
                if ($tgp->getTgtype() == 'supplies') { array_push($output, $tgp); }
                if ($tgp->getTgtype() == 'goods') { array_push($output, $tgp); }
                if ($tgp->getTgtype() == 'gifts') { array_push($output, $tgp); }
            }
        }
        return $output;
    }
}