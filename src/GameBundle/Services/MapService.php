<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/22/2015
 * Time: 11:45 PM
 */

namespace GameBundle\Services;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\Mapzone;
use GameBundle\Game\Model\Tradegood_Token;
use GameBundle\Game\Model\Tradegood_Platonic;
use GameBundle\Game\Rules\Interfaces\IMappable;

class MapService
{

    /** @var DBCommon $db */
    protected $db;

    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return Mapzone
     */
    public function getRandomPassableMapZone()
    {
        $query = "SELECT * FROM mapzone WHERE geotype!='deepsea' AND geotype !='shallowsea' ORDER BY Rand() LIMIT 1;";
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObject();

        return $loadObj;
    }

    /**
     * @param $user IMappable
     * @return Mapzone
     */
    public function GetARandomMove(IMappable &$user, $distance = 1) {

        $query = 'SELECT * FROM mapzone WHERE x>=' .($user->x - $distance). ' AND y>=' .($user->y - $distance).' AND x<=' .($user->x + $distance). ' AND y<=' .($user->y + $distance). ' ORDER BY Rand() LIMIT 1;';
        $this->db->setQuery($query);
        $this->db->query();
        return $this->db->loadObject();
    }

    /**
     * @param Mapzone $mz
     * @return Array
     */
    public function searchZoneForTradegoodTokens(Mapzone $mz) {

        $query = 'SELECT * FROM tradegood_token WHERE mapzone="' .$mz->getId(). '";';
        $this->db->setQuery($query);
        $this->db->query();
        return $this->db->loadObjectList();
    }

    /**
     * @return Tradegood_Platonic
     */
    public function getARandomTradegoodPlatonic()
    {
        $query = 'SELECT * from tradegood_platonic ORDER BY Rand() LIMIT 1;';
        $this->db->setQuery($query);
        $this->db->query();
        return $this->db->loadObject();
    }

    /**
     * @param Mapzone $mz
     * @param Tradegood_Platonic $tg
     */
    public function insertANewTradegoodToken(Mapzone $mz, Tradegood_Platonic $tg)
    {
        $query = 'INSERT INTO tradegood_token(mapzone, tg) VALUES(' . $mz->getId() . ', ' . $tg->getId() . ');';
        $this->db->setQuery($query);
        $this->db->query();
    }
}