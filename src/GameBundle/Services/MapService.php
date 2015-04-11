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
use GameBundle\Game\Model\City;
use GameBundle\Game\Rules\Interfaces\IMappable;

/**
 * Class MapService
 * @package GameBundle\Services
 */
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
     * Returns a hydrated mapzone object for abstract coords $x and $y
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

    /**
     * Returns the first city found within the selected range
     *
     * @param int $x
     * @param int $y
     * @param int width
     * @return City|null
     */
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

    /**
     * Teleports the input IMappable to the input City
     *
     * @param IMappable $mappable
     * @param City $city
     */
    public function teleportCity(IMappable $mappable, City $city)
    {
        $query = 'UPDATE ' .$this->getClass($mappable). ' SET x=' .$city->getX(). ' WHERE id=' .$mappable->getId(). ';';
        $this->db->setQuery($query);
        $this->db->query();
        $query = 'UPDATE ' .$this->getClass($mappable). ' SET y=' .$city->getY(). ' WHERE id=' .$mappable->getId(). ';';
        $this->db->setQuery($query);
        $this->db->query();
    }

    /**
     * @param $issuer
     * @return string
     */
    private function getClass($issuer)
    {
        $getclass = explode('\\', get_class($issuer));
        $name = array_pop($getclass);
        return strtolower($name);
    }

    /**
     * @param string $tablename
     * @param int $topleftid
     * @param int $width
     * @param int $height
     * @param int $limit Optional
     * @return Array
     */
    public function getMapObjectsByViewport($tablename, $topleftid, $width, $height, $limit = null) {
        $query = "SELECT * FROM  mapzone WHERE id=" .$topleftid. ";";
        $this->db->setQuery($query);
        $this->db->query();
        $cz = $this->db->loadObject();

        $query = "SELECT * FROM " .$tablename. " WHERE (x>=" . $cz->x . " AND x<=(" . $cz->x . "+" .$width. ")) AND (y>=" . $cz->y . " AND y<=(" . $cz->y . "+" .$height. ")) ";
        if (is_numeric($limit)) { $query .= " LIMIT " .$limit; }
        $query .= ";";

        $this->db->setQuery($query);
        $this->db->query();
        $objects = $this->db->loadObjectList();

        // Sanitize the abstract coords for mass consumption
        foreach ($objects as $row) {
            $row->x = $row->x - $cz->x;
            $row->y = $row->y - $cz->y;
        }

        return $objects;
    }

    /**
     * @param IMappable $issuer
     * @param $x2
     * @param $y2
     * @return string
     */
    public function mapTravel(IMappable $issuer, $x2, $y2)
    {
        $tablename = $this->getClass($issuer);
        $query = 'UPDATE ' .strtolower($tablename). ' SET x=' .$x2. ' WHERE id=' .$issuer->getId(). ';';
        $this->db->setQuery($query);
        $this->db->query();
        $query = 'UPDATE ' .strtolower($tablename). ' SET y=' .$y2. ' WHERE id=' .$issuer->getId(). ';';
        $this->db->setQuery($query);
        $this->db->query();
        $result = $tablename . $issuer->getId() . ' traveled to ' . $x2 . ', ' . $y2;
        return $result;
    }

    /**
     * Takes x1,y1 and x2,y2, returns if move is legal
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     * @param int $maxdist optional, default is one
     * @return bool
     */
    public function checkLegalMove($x1, $y1, $x2, $y2, $maxdist = 1)
    {
        $maxdist += 1;
        if (abs(($x1 - $x2) < $maxdist) & (abs($y1 - $y2)) < $maxdist)
        {
            $geotype = $this->getGeotypeByMapzone($x2, $y2);
            if ($geotype == 'plains' | $geotype == 'forest' | $geotype == 'desert' |
                $geotype == 'hills' | $geotype == 'mountain' | $geotype == 'swamp')
            {
                return true;
            } else {
                return false;
            }
        }
    }
}