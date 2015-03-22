<?php

namespace GameBundle\Game\Rules;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\Clan;

use GameBundle\Game\Rules\EnforceParams\ClanParams;

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
        $ClanParam = new ClanParams();
        $ClanParam->setDb($this->db);
        $ClanParam->trashDeadObjects();
        $this->status .= $ClanParam->getStatus();
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