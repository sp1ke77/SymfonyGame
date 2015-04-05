<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 4/2/2015
 * Time: 1:15 AM
 */

namespace GameBundle\Game\Simulation;
use GameBundle\Game\Simulation\AI\Clans\Behavior;
use GameBundle\Services\TribeService;
use GameBundle\Game\DBCommon;

class ActionRound {

    /** @var $db DBCommon */
    protected $db;

    /** @param $db DBCommon */
    public function setDb($db) {
        $this->db = $db;
    }

    public function execute()
    {
        $behavior = new Behavior();
        $behavior->setDb($this->db);
        $tribeService = new TribeService();
        $tribeService->setDb($this->db);

        $clans = $tribeService->getAllClans();

        $result=[];
        foreach ($clans as $clan) {
            $result[] = $behavior->TakeAction($clan->id);
        }
        return $result;
    }
}