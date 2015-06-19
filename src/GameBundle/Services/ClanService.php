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

    public function setClanCurrentCity(Clan $clan, City $city) {
        $query = "UPDATE game.clan SET city=" .$city->getId(). ' WHERE id=' .$clan->getId(). ';';
        $this->db->setQuery($query);
        $this->db->query();
    }
}