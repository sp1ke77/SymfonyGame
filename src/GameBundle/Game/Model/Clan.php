<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/20/2015
 * Time: 1:43 PM
 */

namespace GameBundle\Game\Model;

use GameBundle\Game\DBCommon;
use GameBundle\Game\Rules\IMappable;

/**
 * Class Clan
 * @package GameBundle\Game
 */
class Clan implements IMappable
{
    /**
     * Keys
     * @var int $clanId
     */
    protected $clanId;

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
     * Components
     * @var DBCommon $db
     */
    protected $db;

    /**
     * Constructor
     * @param $clanId
     */
    public function __construct($clanId)
    {
        if (!isset($clanId))
        {
            return;
        } else {
            $this->clanId = $clanId;
        }
    }

    /**
     * @param $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * Hydrate the object
     */
    public function load()
    {
        $query = "SELECT * FROM clan WHERE id=" . $this->clanId . ";";
        $this->db->setQuery($query);
        $queryObj = $this->db->loadObject();

        if (!empty($queryObj)) {
            $this->population = $queryObj->population;
            $this->fighters = $queryObj->fighters;
            $this->coin = $queryObj->coin;
            $this->food = $queryObj->food;
            $this->tribeId = $queryObj->tribe;
            $this->depotId = $queryObj->depot;
            $this->x = $queryObj->x;
            $this->y = $queryObj->y;
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->clanId;
    }

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

    /**
     * @return mixed
     */
    public function getDepotId()
    {
        return $this->depotId;
    }

    /**
     * @return mixed
     */
    public function getTribeId()
    {
        return $this->tribeId;
    }


}