<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/20/2015
 * Time: 1:43 PM
 */
namespace GameBundle\Game;
use GameBundle\Game\DBCommon;

/**
 * Class Clan
 * @package GameBundle\Game
 */
class Clan
{
    /**
     * Keys
     * @var int $clanId
     */
    protected $clanId;

    /**
     * Fields
     * @var $population
     * @var $fighters
     * @var $coin
     * @var $food
     */
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
        }
    }

    /**
     * @return int
     */
    public function getClanId()
    {
        return $this->clanId;
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