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

    /** @var $db DBCommon */
    protected $db;

    /** @param $db DBCommon */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return Mapzone
     */
    public function getRandomPassableMapZone()
    {
        $query = "SELECT id FROM mapzone WHERE geotype!='deepsea' AND geotype !='shallowsea' ORDER BY Rand() LIMIT 1;";
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObject();

        $mz = new Mapzone($loadObj->id);
        $mz->setDb($this->db);
        $mz->load();

        return $mz;
    }

    /**
     * @param $user IMappable
     * @param $distance int
     * @return Mapzone
     */
    public function GetARandomMove(IMappable $user, $distance = 1)
    {
        $query = 'SELECT id FROM mapzone WHERE x>=' .($user->getX() - $distance). ' AND y>=' .($user->getY() - $distance).' AND x<=' .($user->getX() + $distance). ' AND y<=' .($user->getY() + $distance). ' ORDER BY Rand() LIMIT 1;';
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObject();

        $mz = new Mapzone($loadObj->id);
        $mz->setDb($this->db);
        $mz->load();

        return $mz;
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

    /**
     * @param $x
     * @param $y
     * @return string
     */
    public function getGeotypeByMapzone($x, $y) {
        $query = "SELECT geotype FROM mapzone WHERE x=" .$x. " AND y=" .$y. ";";
        $this->db->setQuery($query);
        $this->db->query();
        return $this->db->loadResult();
    }
}