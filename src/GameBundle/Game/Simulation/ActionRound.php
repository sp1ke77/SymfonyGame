<?php

namespace GameBundle\Game\Simulation;

use GameBundle\Game\Exceptions\MissingPropertyException;

/**
 * Class ActionRound
 * @package GameBundle\Game\Simulation
 */
class ActionRound extends BaseSimulation
{
    /**
     * @throws MissingPropertyException
     * @return array
     */
    public function execute()
    {
        if(!isset($this->behavior)){
            throw new MissingPropertyException('behavior is required');
        }

        if(!isset($this->tribeservice)){
            throw new MissingPropertyException('tribe service is required');
        }

        $clans = $this->tribeservice->getAllClans();

        $result = [];
        foreach ($clans as $clan) {
            $result[] = $this->behavior->TakeAction($clan->getId());
        }

        return $result;
    }
}