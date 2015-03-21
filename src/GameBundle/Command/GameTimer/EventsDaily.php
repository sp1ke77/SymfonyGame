<?php

namespace GameBundle\Command\GameTimer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GameBundle\Game\DBCommon;

class EventsDaily extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('GameTimer:EventsDaily')
            ->setDescription('Recurs daily to trigger more complex, interactive events, especially for ' .
                                'player characters.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var DBCommon $db */
        $db = $this->getContainer()->get('db');


    }
}