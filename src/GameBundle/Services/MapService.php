<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/22/2015
 * Time: 11:45 PM
 */

namespace GameBundle\Services;
use GameBundle\Game\DBCommon;

class MapService
{

    /** @var DBCommon $db */
    protected $db;

    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return object
     */
    public function getRandomPassableMapZone()
    {
        $query = "SELECT * FROM mapzone WHERE geotype!='deepsea' AND geotype !='shallowsea' ORDER BY Rand() LIMIT 1;";
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObject();

        return $loadObj;
    }

}