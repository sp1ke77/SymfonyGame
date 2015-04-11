<?php

namespace GameBundle\Game\Simulation;

use GameBundle\Services\TribeService;

use GameBundle\Game\Simulation\AI\Clans\Behavior;

class BaseSimulation
{


    /** @var DBCommon */
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
     * @var MapService
     */
    protected $map;

    /**
     * @var NewsService
     */
    protected $news;

    /**
     * @param DBCommon $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @param MapService $map
     */
    public function setMap($map)
    {
        $this->map = $map;
    }

    /**
     * @param NewsService $news
     */
    public function setNews($news)
    {
        $this->news = $news;
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