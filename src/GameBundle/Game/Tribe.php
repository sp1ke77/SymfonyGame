<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/20/2015
 * Time: 2:07 PM
 */
namespace GameBundle\Game;

/**
 * Class Tribe
 * @package GameBundle\Game
 */
class Tribe
{
    /**
     * @var
     */
    protected $db;
    /**
     * @var
     */
    protected $tribeId;

    /**
     * @var
     */
    protected $culture;

    /**
     * @var string
     */
    protected $named;

    /**
     * @param $tribeId
     */
    function __construct($tribeId)
    {
        if (!isset($tribeId))
        {
            return;
        } else {
            $this->tribeId = $tribeId;
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
     *  Hydrate this object from the db
     */
    public function load()
    {
        $query = "SELECT * FROM tribe WHERE id=" . $this->tribeId . ";";
        $this->db->setQuery($query);
        $queryObj = $this->db->loadObject();

        if (!empty($queryObj)) {
            $this->culture = $queryObj->culture;
            $this->named = $queryObj->named;
        }
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @return int
     */
    public function getTribeId()
    {
        return (int)$this->tribeId;
    }

    /**
     * @return mixed
     */
    public function getCulture()
    {
        return $this->culture;
    }

    /**
     * @return mixed
     */
    public function getNamed()
    {
        return $this->named;
    }
}