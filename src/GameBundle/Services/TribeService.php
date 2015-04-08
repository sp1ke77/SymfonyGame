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

class TribeService
{

    /** @var $db DBCommon */
    protected $db;

    /**
     * @param $db DBCommon
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /** @return Array|Clan */
    public function getAllClans() {
        $query = 'SELECT * FROM clan;';
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
        $MapService = new MapService();
        $MapService->setDb($this->db);
        $mz = $MapService->getRandomPassableMapZone();
        $x = $mz->getX();
        $y = $mz->getY();
        $query = "INSERT INTO clan(named, tribe, x, y, depot, population, fighters, morale, food, coin, activity) VALUES('"
            .$tribeName. "', " .$tribeId. ", " .$x. ', ' .$y. ', ' .$depotId. ", 100, 60, 100, 35, 0, 'wandering');";
        $this->db->setQuery($query);
        $this->db->query();
    }

}