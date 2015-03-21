<?php

namespace GameBundle\Command\GameTimer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GameBundle\Game\DBCommon;

class EventsRandom extends ContainerAwareCommand
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
        /** @var DBCommon $db */
        $db = $this->getContainer()->get('db');


    }
}