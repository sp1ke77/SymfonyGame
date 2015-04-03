<?php

namespace GameBundle\Game\Simulation\EnforceParams;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\Clan;
use GameBundle\Game\Simulation\EnforceParams\GarbageCollector;

class EnforceParams
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

    public function enforce()
    {
        $janitor = new GarbageCollector();
        $janitor->setDb($this->db);
        $janitor->trashDeadObjects();
        $this->status .= $janitor->getStatus();
    }


    /*
     *
     *
     *              PRIVATE FUNCTIONS
     *
     *
     *
     */


}