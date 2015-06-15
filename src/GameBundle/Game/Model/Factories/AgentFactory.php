<?php

namespace GameBundle\Game\Model\Factories;

use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\Agent;

class AgentFactory
{
    /** @var DBCommon $db */
    protected $db;

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param mixed $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @param null $userid
     * @param $named
     * @param $culture
     * @param $city
     * @param $allegiance
     * @return int
     */
    public function factory($userid = null, $named, $culture, $city, $allegiance)
    {
        $id = null;
        if (is_null($userid))
        {
            // this is a procedural, NPC agent
            // ignore all the other inputs and randomize
        } else {
            $id = $this->newPlayerAgent($userid, $named, $culture, $city, $allegiance);
        }
        return $id;
    }

    /**
     * @param $userid
     * @param $named
     * @param $culture
     * @param $city
     * @param $allegiance
     * @return int
     */
    private function newPlayerAgent($userid, $named, $culture, $city, $allegiance) {

        $agent = new Agent(null);
        $agent->setUserid($userid);
        $agent->setNamed($named);
        $agent->setCulture($culture);

        $query = 'SELECT * FROM game.city WHERE named="' .$city. '";';
        $this->db->setQuery($query);
        $this->db->query();
        $dbObject = $this->db->loadObject();

        $agent->setX($dbObject->x);
        $agent->setY($dbObject->y);
        $agent->setCity($dbObject->id);
        $agent->setActivity('idle');

        $query = 'INSERT INTO game.buildinglist(estate) VALUES(1);';
        $this->db->setQuery($query);
        $this->db->query();
        $agent->setHoldings($this->db->getLastInsertId());

        $query = 'INSERT INTO game.persona(fame, honor, controversy) VALUES(0,0,0);';
        $this->db->setQuery($query);
        $this->db->query();
        $agent->setPersona($this->db->getLastInsertId());

        $agent->setAllegiance($allegiance);

        $agent->update();
        return $this->db->getLastInsertId();
    }
}