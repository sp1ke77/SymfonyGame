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

    /** @return Clan */
    function getAllClans() {
        $query = 'SELECT * FROM clan;';
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObjectList();
        foreach ($loadObj as $obj) {
            $clan = New Clan($obj->id);
            $clan->load();
            $clans[] = $clan;
        }
        return $this->db->loadObjectList();
    }

}