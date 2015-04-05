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
use GameBundle\Game\Model\TradegoodToken;
use GameBundle\Game\Model\TradegoodPlatonic;
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
        $tokens = [];
        $query = 'SELECT id FROM tradegoodtoken WHERE mapzone="' .$mz->getId(). '";';
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObjectList();
        foreach ($loadObj as $obj) {
            $tgt = new TradegoodToken($obj->id);
            $tgt->setDb($this->db);
            $tgt->load();
            $tokens[] = $tgt;
        }
        return $tokens;
    }

    /**
     * @return TradegoodPlatonic
     */
    public function getARandomTradegoodPlatonic()
    {
        $query = 'SELECT id from tradegoodplatonic ORDER BY Rand() LIMIT 1;';
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObject();
        $tradegood = new TradegoodPlatonic($loadObj->id);
        $tradegood->setDb($this->db);
        $tradegood->load();
        return $tradegood;
    }

    /**
     * @param Mapzone $mz
     * @param TradegoodPlatonic $tg
     */
    public function insertANewTradegoodToken(Mapzone $mz, TradegoodPlatonic $tg)
    {
        $query = 'INSERT INTO tradegoodtoken(mapzone, tg) VALUES(' . $mz->getId() . ', ' . $tg->getId() . ');';
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