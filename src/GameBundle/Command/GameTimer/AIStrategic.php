<?php

namespace GameBundle\Command\GameTimer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GameBundle\Game\DBCommon;

class EnforceParams extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('GameTimer:AIStrategic')
            ->setDescription('Recurs every 5 hours to perform more complex and longterm considerations
                on the part of clans, agents, tribes and nations.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var DBCommon $db */
        $db = $this->getContainer()->get('db');


    }
}