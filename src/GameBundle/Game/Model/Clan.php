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
     * @var $population
     * @var $fighters
     * @var $coin
     * @var $food
     * @var $tribeName
     */
    protected $x, $y;
    protected $population;
    protected $fighters;
    protected $coin;
    protected $food;

    /**
     * References
     * @var $depotId
     * @var $tribeId
     */
    protected $depotId;
    protected $tribeId;

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
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
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return mixed
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * @return mixed
     */
    public function getFighters()
    {
        return $this->fighters;
    }

    /**
     * @return mixed
     */
    public function getFood()
    {
        return $this->food;
    }

    /**
     * @return mixed
     */
    public function getCoin()
    {
        return $this->coin;
    }

    /*
     *  IDepotHaver implementation
     *
     */
    /**
     * @return mixed
     */
    public function getDepot()
    {
        return $this->depotId;
    }

    /*
     *
     *  ICombatant implementation
     *
     */
    /**
     * @return mixed
     */
    public function getTribeId()
    {
        return $this->tribeId;
    }

    public function getMorale()
    {

    }

    public function Attack()
    {

    }

    public function Defend($attack)
    {

    }
}