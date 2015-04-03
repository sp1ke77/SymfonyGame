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

class ActionRound {

    public function execute()
    {
        $behavior = new Behavior();
        $tribeService = new TribeService();

        $clans = $tribeService->getAllClans();

        foreach ($clans as $clan) {
            $behavior->TakeAction($clan->id);
        }
    }
}