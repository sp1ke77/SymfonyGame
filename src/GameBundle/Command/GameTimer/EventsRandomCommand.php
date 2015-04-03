<?php

namespace GameBundle\Command\GameTimer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;

use GameBundle\Game\Simulation\RandomEvents\RandomEvents;

class EventsRandomCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('GameTimer:EventsRandom')
            ->setDescription('Recurs every 5 minutes to run random-mode analytics via the Simmer.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $logger LoggerInterface */
        $logger = $this->getContainer()->get('logger');
        $logger->info('EventsRandom round begun ... ');

        for ($i = 0; $i < 15; $i++) {
            $randomEvents = new RandomEvents();
            $msg = $randomEvents->rollDice();
            if (!empty($msg)) {
                $logger->info($msg);
            } else {
                continue;
            }
        }

        $logger->info('EventsRandom round complete');
    }
}