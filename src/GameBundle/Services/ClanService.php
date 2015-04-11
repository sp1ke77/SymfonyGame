<?php

namespace GameBundle\Services;
use GameBundle\Game\DBCommon;
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
     * @param $id
     * @param $amt
     */
    public function setFood($id, $amt)
    {
        $query = 'UPDATE clan SET food=' .$amt. ' WHERE id=' .$id. ';';
        $this->db->setQuery($query);
        $this->db->query();
    }

    /**
     * @param $id
     * @param $amt
     */
    public function setPopulation($id, $amt)
    {
        $query = 'UPDATE clan SET population=' .$amt. ' WHERE id=' . $id . ';';
        $this->db->setQuery($query);
        $this->db->query();
    }

    /**
     * @param Clan $clan
     * @param $activity
     */
    public function changeActivity(Clan &$clan, $activity)
    {
        $query = 'UPDATE clan SET activity="' . $activity . '" WHERE id=' . $clan->getId() . ';';
        $this->db->setQuery($query);
        $this->db->query();
    }

    /**
     * @param Clan $clan
     * @param $producing
     */
    public function changeProducing(Clan &$clan, $producing)
    {
        $query = 'UPDATE clan SET producing="' . $producing . '" WHERE id=' . $clan->getId() . ';';
        $this->db->setQuery($query);
        $this->db->query();
    }
}