<?php

namespace GameBundle\Game\Simulation\EnforceParams;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\Clan;

class GarbageCollector
{
    /**
     * Components
     * @var DBCommon $db */
    protected $db;

    /**
     * Concatenated output string for logging
     * @var string $status
     */
    protected $status;

    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function trashDeadObjects()
    {
        $getAll[] = $this->getAllDeadClans();
        $clans = $getAll[0];

        if (!isset($clans))
        {
            $this->status .= 'No dead clans found this turn.';
        } else {
            $score = 0;

            foreach ($clans as $clan) {
                // Get the tribe name and pass it along ...
                $query = "SELECT named FROM tribe WHERE id=" . $clan->getClanId() . ";";
                $this->db->setQuery($query);
                $tribeName = $this->db->loadResult();

                // Create some news about the death ...
                $query = "INSERT INTO News(text) VALUE('A clan of the tribe of " . $tribeName . " have dwindled to nothing. Last seen in " . $clan->getX() . ", " . $clan->getY() . "');";
                $this->db->setQuery($query);
                $this->db->Query($query);

                // Rack one up
                $score += 1;

                // Trash the record for the clan
                $query = "DELETE FROM clan WHERE id=" . $clan->getClanId() . ";";
                $this->db->setQuery($query);
                $this->db->Query($query);
            }
            $this->status .= "Clans killed this round: " . $score;
        }
    }


    /*
     *
     *
     *              PRIVATE FUNCTIONS
     *
     *
     *
     */
    /**
     * @return array
     */
    private function getAllDeadClans()
    {
        $clans = [];
        $query = "SELECT id FROM clan WHERE population=0;";
        $this->db->setQuery($query);
        $queryObj = $this->db->loadObjectList();

        foreach ($queryObj as $k => $v)
        {
            $id = $v->id;
            $clan = New Clan($id);
            $clan->setDb($this->db);
            $clan->load();
            $clans[] = $clan;
        }

        return $clans;
    }
}