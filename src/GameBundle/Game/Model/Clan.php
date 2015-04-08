<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/20/2015
 * Time: 1:43 PM
 */

namespace GameBundle\Game\Model;

use GameBundle\Game\Rules\Interfaces\IMappable;
use GameBundle\Game\Rules\Interfaces\IDepotHaver;
use GameBundle\Game\Rules\Interfaces\ICombatant;

/**
 * Class Clan
 * @package GameBundle\Game
 */
class Clan extends GameEntity implements IMappable, IDepotHaver, ICombatant
{
    /**
     * Fields
     * @var int $x, $y
     * @var int $population
     * @var int $fighters
     * @var int $coin
     * @var int $food
     * @var int $morale
     * @var int $activity
     */
    protected $x, $y;
    protected $named;
    protected $population;
    protected $fighters;
    protected $coin;
    protected $food;
    protected $morale;
    protected $activity;
    protected $producing;

    /**
     * References
     * @var $depot
     * @var $tribe
     */
    protected $depot;
    protected $tribe;

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @param int $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * @return int
     */
    public function getFighters()
    {
        return $this->fighters;
    }

    /**
     * @return int
     */
    public function getFood()
    {
        return $this->food;
    }

    public function setFood($food)
    {
        $this->food = $food;
    }

    /**
     * @param mixed $population
     */
    public function setPopulation($population)
    {
        $this->population = $population;
    }

    /**
     * @param $coin int
     */
    public function setCoin($coin)
    {
        $this->coin = $coin;
    }

    /**
     * @param mixed $morale
     */
    public function setMorale($morale)
    {
        $this->morale = $morale;
    }

    /**
     * @return string
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
     * @param mixed $fighters
     */
    public function setFighters($fighters)
    {
        $this->fighters = $fighters;
    }
    /**
     * @return int
     */
    public function getCoin()
    {
        return $this->coin;
    }

    /**
     * @return int
     */
    public function getTribeId()
    {
        return $this->tribe;
    }

    /*
     *  IDepotHaver implementation
     *
     */
    /**
     * @return int
     */
    public function getDepot()
    {
        return $this->depot;
    }

    /*
     *
     *  ICombatant implementation
     *
     */

    /**
     * @return int
     */
    public function getMorale()
    {

    }

    /**
     * @return mixed
     */
    public function getProducing()
    {
        return $this->producing;
    }

    /**
     * @param mixed $producing
     */
    public function setProducing($producing)
    {
        $this->producing = $producing;
    }

    public function Attack()
    {

    }

    public function Defend($attack)
    {

    }
}