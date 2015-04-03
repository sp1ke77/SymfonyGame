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
     * @var $morale
     * @var $activity
     */
    protected $x, $y;
    protected $named;
    protected $population;
    protected $fighters;
    protected $coin;
    protected $food;
    protected $morale;
    protected $activity;

    /**
     * References
     * @var $depotId
     * @var $tribeId
     */
    protected $depotId;
    protected $tribeId;

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
     * @param int
     */

    public function setFood($food)
    {
        $this->food = $food;
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
        return $this->tribeId;
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
        return $this->depotId;
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

    public function Attack()
    {

    }

    public function Defend($attack)
    {

    }

    /*
     *
     *
     *
     *               CLAN SPECIFIC FUNCTIONS
     *
     *
     *
     */

    public function checkLarder()
    {
        $depot = New Depot($this->depotId);
        $depot->load();

        // This should be made more extensible, probably by using reflection some more
        $this->food = (($depot->getWheat() * 1) + ($depot->getOlives() * 1.2) +
                        ($depot->getFish() * 1.2) + ($depot->getCattle() * 2.8));
        $depot->setWheat(0);
        $depot->setOlives(0);
        $depot->setFish(0);
        $depot->setCattle(0);
        $depot->update();
    }

    public function consumeFood($amt)
    {
        if ($this->food > $amt) {
            $this->food -= $amt;
            $this->update();
            return true;
        } else {
            // Generate some news about famine and bad shit
            $this->population -= 1;
            $this->update();
            return false;
        }
    }
}