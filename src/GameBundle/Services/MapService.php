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
use GameBundle\Game\Model\City;
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
     * @return Array|TradegoodToken
     */
    public function searchZoneForTradegoodTokens(Mapzone $mz) {
        $tokens = [];
        $query = 'SELECT id FROM tradegoodtoken WHERE mapzone=' .$mz->getId(). ';';
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
     * Returns the id of the most valuable game.tradegoodplatonic in the mapzone
     *
     * @param Mapzone $mz
     * @return int
     */
    public function exploreForTrade(Mapzone $mz)
    {
        $query = 'SELECT tg FROM tradegoodtoken WHERE mapzone=' .$mz->getId(). ' ORDER BY tradevalue ASC LIMIT 1;';
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObject();
        if (isset($loadObj)) {
            return $loadObj->tg;
        } else {
            return null;
        }
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
        $query = 'INSERT INTO tradegoodtoken(mapzone, tg, named, tradevalue, foodvalue) VALUES(' .$mz->getId(). ', ' .$tg->getId(). ', "' .$tg->getNamed(). '", ' .$tg->getTradevalue(). ', ' .$tg->getFoodvalue(). ');';
        $this->db->setQuery($query);
        $this->db->query();
    }

    /**
     * @param $x
     * @param $y
     * @return string
     */
    public function getGeotypeByMapzone($x, $y)
    {
        $query = "SELECT geotype FROM mapzone WHERE x=" .$x. " AND y=" .$y. ";";
        $this->db->setQuery($query);
        $this->db->query();
        return $this->db->loadResult();
    }

    /**
     * @param $x
     * @param $y
     * @return Mapzone
     */
    public function getMapzoneFromAbstract($x, $y)
    {
        $query = "SELECT * FROM mapzone WHERE x=" .$x. " AND y=" .$y. ";";
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObject();
        $mapzone = new Mapzone($loadObj->id);
        $mapzone->setDb($this->db);
        $mapzone->load();
        return $mapzone;
    }

    public function findNearestCity($x, $y) {
        $query = "SELECT id FROM city WHERE x>=" .($x - 5). " AND x<=" .($x + 5). " AND y>= " .($y - 5). " AND y<=" .($y + 5). " LIMIT 1;";
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObject();

        if (isset($loadObj)) {
            $city = new City($loadObj->id);
            $city->setDb($this->db);
            $city->load();
            return $city;
        } else {
            return null;
        }
    }

    public function teleportCity(IMappable $mappable, City $city)
    {
        $query = 'UPDATE ' .$this->getClass($mappable). ' SET x=' .$city->getX(). ' AND y=' .$city->getY(). ' WHERE ' .$mappable->getId(). ';';
        $this->db->setQuery($query);
        $this->db->query();
    }

    private function getClass($issuer)
    {
        $getclass = explode('\\', get_class($issuer));
        $name = array_pop($getclass);
        return strtolower($name);
    }
}