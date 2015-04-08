<?php

namespace GameBundle\Command\GameTimer;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use GameBundle\Game\Simulation\ActionRound;

/**
 * Class ActionRoundCommand
 * @package GameBundle\Command\GameTimer
 */
class ActionRoundCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('GameTimer:ActionRound')
            ->setDescription('Recurs every 10 minutes to process action queues on the part of characters, '.
                'agents, units and clans.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getContainer()->get('logger');

        $logger->info('ActionRound begun ... ');

        /** @var  ActionRound $action */
        $action = $this->getContainer()->get('service_action_round');
        $result = $action->execute();

        $logger->info(print_r($result));
        $logger->info('ActionRound complete ... ');
    }
}