<?php

namespace GameBundle\Game\Simulation;

use GameBundle\Services\TribeService;

use GameBundle\Game\Simulation\AI\Clans\Behavior;

class BaseSimulation
{


    /** @var $db DBCommon */
    protected $db;

    /**
     * @var Behavior
     */
    protected $behavior;

    /**
     * @var TribeService
     */
    protected $tribeservice;

    /**
     * @param DBCommon $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @param Behavior $behavior
     */
    public function setBehavior($behavior)
    {
        $this->behavior = $behavior;
    }

    /**
     * @param TribeService $tribeservice
     */
    public function setTribeservice($tribeservice)
    {
        $this->tribeservice = $tribeservice;
    }
}