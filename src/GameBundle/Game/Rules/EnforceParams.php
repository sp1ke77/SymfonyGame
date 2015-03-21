<?php

namespace GameBundle\Game\Rules;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Clan;

class EnforceParams
{
    /** @var DBCommon $db */
    protected $db;

    public function trashDeadObjects()
    {
        $getAll[] = $this->getAllDeadObjects();

        if (empty($getAll)) { exit; }

        $clans = $getAll[0];

        foreach ($clans as $clan)
        {
                // Get the tribe name and pass it along ...

                $query = "SELECT named FROM tribe WHERE id=" . $clan->getClanId() . ";";
                $this->db->setQuery($query);
                $tribeName = $this->db->loadResult();

                // Create some news about the death ...

                $query = "INSERT INTO News(text) VALUE('A clan of the tribe of " . $tribeName . " are annihilated, their seed are dust.');";
                $this->db->setQuery($query);
                $this->db->Query($query);

                // Trash the record for the clan

                $query = "DELETE FROM clan WHERE id=" . $clan->getClanId() . ";";
                $this->db->setQuery($query);
                $this->db->Query($query);
        }
    }

    public function setDb($db)
    {
        $this->db = $db;
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
    private function getAllDeadObjects()
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