<?php

namespace GameBundle\Command\GameTimer;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use GameBundle\Game\Model\Agent;
use GameBundle\Game\Model\Estate;
use GameBundle\Game\Model\City;
use GameBundle\Game\Simulation\ActionRound;

/**
 * Class ActionRoundCommand
 * @package GameBundle\Command\GameTimer
 */
class DailyBudgetsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('GameTimer:DailyBudgets')
            ->setDescription('Recurs every day and carries out daily distributions, taxes, etc. for the economic sim.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getContainer()->get('logger');

        $logger->info('DailyBudgets begun ... ');

        $map = $this->getContainer()->get('service_map');
        $agents = $this->getContainer()->get('service_agent');
        $db = $this->getContainer()->get('db');
        $allCities = $map->getCitiesByID();
        foreach($allCities as $k => $c) {
            $city = new City($c->id);
            $city->setDb($db);
            $city->load();
            $agentList = $agents->getAgentsByCity($c->id);
            foreach ($agentList as $j => $a) {
                $agent = new Agent($a->id);
                $agent->setDb($db);
                $agent->load();
                $agent->setTradeincome($city->getTradeincome());
                $income = $agent->getCoin() + ceil($city->getTradeincome());
                $logger->info('The estate of ' .$agent->getNamed(). ' collected ' .$city->getTradeincome(). ' from nearby markets');
                $agent->setCoin($income);
                $agent->update();
            }
            $city->setTradeincome(0.00);
            $city->update();
        }

        $logger->info('DailyBudgets complete ... ');
    }
}