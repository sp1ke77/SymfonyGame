<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 4/3/2015
 * Time: 1:43 PM
 */

namespace GameBundle\Services;
use GameBundle\Game\Model\Clan;
use GameBundle\Game\Model\Tribe;

class TribeService
{

    protected $db;

    public function setDb($db)
    {
        $this->db = $db;
    }

    function getAllClans() {
        $query = 'SELECT * FROM clan;';
        $this->db->setQuery($query);
        $this->db->query();
        return $this->db->loadObjectList();
    }

}