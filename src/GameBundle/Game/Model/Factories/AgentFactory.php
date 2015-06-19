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
        // The exposed front of this class is simply a strategy
        // Invoked with no userid, we assume an NPC agent is being created by the
        // simulation and randomize any fields.
        // If a userid is given, we assume a player character.

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

        // Create an undefined object to be assembled and pass it the DB
        $agent = new Agent(null);
        $agent->setDb($this->db);

        // Plunk, plonk, plonk
        $agent->setIsplayer(true);
        $agent->setUserid($userid);
        $agent->setNamed($named);
        $agent->setCulture($culture);

        // Get the player's chosen city out of the DB
        $query = 'SELECT * FROM game.city WHERE named="' .$city. '";';
        $this->db->setQuery($query);
        $this->db->query();
        $dbObject = $this->db->loadObject();

        // Plunk, plonk, city data
        $agent->setX($dbObject->x);
        $agent->setY($dbObject->y);
        $agent->setCity($dbObject->id);
        $agent->setActivity('idle');

        // Create a new buildinglist and point the value of $this->holdings to its primary key
        $query = 'INSERT INTO game.estate(id) VALUES(null);';
        $this->db->setQuery($query);
        $this->db->query();
        $agent->setHoldings($this->db->getLastInsertId());

        // Ditto for a blank persona
        $query = 'INSERT INTO game.persona(id) VALUES(null);';
        $this->db->setQuery($query);
        $this->db->query();
        $agent->setPersona($this->db->getLastInsertId());

        // Plunk
        $agent->setAllegiance($allegiance);

        // Insert the new Agent into SQL and return the primary key
        $agent->update();
        return $this->db->getLastInsertId();
    }
}