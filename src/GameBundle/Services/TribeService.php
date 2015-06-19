<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 4/3/2015
 * Time: 1:43 PM
 */

namespace GameBundle\Services;

use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\Clan;
use GameBundle\Services\MapService;

class TribeService
{

    /**
     * Components
     * @var DBCommon
     * @var MapService
     */
    protected $db;
    protected $map;

    /**
     * @param DBCommon
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @param $mapService
     */
    public function setMap($mapService)
    {
        $this->map = $mapService;
    }

    /** @return Array */
    public function getAllClanIDs() {
        $query = 'SELECT id FROM clan;';
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObjectList();
        $clans = [];
        foreach ($loadObj as $obj) {
            $clan = New Clan($obj->id);
            $clan->setDb($this->db);
            $clan->load();
            $clans[] = $clan;
        }
        return $clans;
    }

    public function createClan($tribeId)
    {
        $depotId = $this->createDepot();
        $query = "SELECT named FROM tribe WHERE id=" . $tribeId . ";";
        $this->db->setQuery($query);
        $this->db->query();
        $tribeName = $this->db->loadResult();

        // This will eventually point to some logic for placing clans by culture-group
        $mz = $this->map->getRandomPassableMapZone();
        $x = $mz->getX();
        $y = $mz->getY();

        $query = "INSERT INTO clan(named, tribe, x, y, depot, population, fighters, morale, food, coin, activity) VALUES('"
            .$tribeName. "', " .$tribeId. ", " .$x. ', ' .$y. ', ' .$depotId. ", 100, 60, 100, 35, 0, 'wandering');";
        $this->db->setQuery($query);
        $this->db->query();
    }

}