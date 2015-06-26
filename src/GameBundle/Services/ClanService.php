<?php

namespace GameBundle\Services;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\City;
use GameBundle\Game\Model\Clan;


/**
 * Class ClanService
 * @package GameBundle\Services
 */
class ClanService
{
    /**
     * @var DBCommon $db
     */
    protected $db;

    /** @param DBCommon */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @param Clan $clan
     * @param City $city
     */
    public function setClanCurrentCity(Clan $clan, City $city) {
        $query = "UPDATE game.clan SET city=" .$city->getId(). ' WHERE id=' .$clan->getId(). ';';
        $this->db->setQuery($query);
        $this->db->query();
    }

    /**
     * @param City $city
     * @return array
     */
    public function getClansByCity(City $city)
    {
        $query = "SELECT * FROM game.clan WHERE city=" .$city->getId(). ';';
        $this->db->setQuery($query);
        $this->db->query();
        $objList = $this->db->loadObjectList();
        $clans = null;
        foreach ($objList as $obj) {
            $clan = new Clan($obj->id);
            $clan->setDb($this->db);
            $clan->load();
            $clans[] = $clan;
        }
        return $clans;
    }

    public function getClanName(Clan $clan) {
        $query = "SELECT named FROM game.tribe WHERE id=" .$clan->getTribeId(). ';';
        $this->db->setQuery($query);
        $this->db->query();
        return $this->db->loadObject();
    }
}