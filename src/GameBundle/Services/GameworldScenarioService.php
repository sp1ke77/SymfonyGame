<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/19/2015
 * Time: 7:55 PM
 *
 *  This will eventually be a wrapper for the scenario.json that is used to generate newgames.
 *  For now we're going to hardcode it.
 *
 */

namespace GameBundle\Services;
use GameBundle\Game\DBCommon;
use \Exception as Exception;

class GameworldScenarioService
{
    /**
     * @var DBCommon $db
     */

    protected $db;

    /**
     * @param DBCommon $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    public function randomizeMap()
    {
        // Create 1600 map zones and link them with a discrete x,y

        for ($i = 0; $i < 400; $i++)
        {
            // Create the mapzone

            $query = "INSERT INTO mapzone(geotype) VALUE (" . rand(1, 8) . ");";
            $this->db->setQuery($query);
            $this->db->query();

            $mapzoneId = $this->db->getLastInsertId();
            $x = $i % 20;
            $y = $i / 20;

            $query = "INSERT INTO map(x, y, mapzone) VALUE (" . $x . ", " . $y . ", " . $mapzoneId .");";
            $this->db->setQuery($query);
            $this->db->query();

        }
    }

    public function initializeCities()
    {


    }

    public function initializeRegions()
    {


    }

    public function initializeTradeGoods()
    {


    }

    public function createSomeTribes()
    {


    }

    public function createSomeClans()
    {


    }


}
