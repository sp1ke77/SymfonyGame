<?php

namespace GameBundle\Services;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\Agent;
use \Exception as Exception;

class AgentService {

    /** @var DBCommon $db */
    protected $db;

    function getMapviewID($agentid)
    {
        $character = new Agent($agentid);
        $character->setDb($this->db);
        $character->load();

        $topleft['x'] = $character->getX() - 5;
        $topleft['y'] = $character->getY() - 3;

        $query = "SELECT id FROM game.mapzone WHERE x=" .$topleft['x']. " AND y=" .$topleft['y']. ";";
        $this->db->setQuery($query);
        $this->db->query();
        $mvid = $this->db->loadResult();

        return $mvid;
    }

    /**
     * @return DBCommon
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param DBCommon $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }


    public function getAllAgentIDs()
    {
        $query = 'SELECT id FROM game.agent;';
        $this->db->setQuery($query);
        $this->db->query();
        return $this->db->loadObjectList();
    }

    public function getAllEstateIDs()
    {
        $query = 'SELECT id FROM game.estate;';
        $this->db->setQuery($query);
        $this->db->query();
        return $this->db->loadObjectList();
    }

    public function getAgentsByCity($cityID)
    {
        $query = 'SELECT id FROM game.agent WHERE city=' .$cityID;
        $this->db->setQuery($query);
        $this->db->query();
        return $this->db->loadObjectList();
    }
}