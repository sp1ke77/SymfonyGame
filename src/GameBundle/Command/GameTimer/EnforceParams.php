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
            ->setName('GameTimer:EnforceParams')
            ->setDescription('Recurs every 2 minutes to crawl the db and enforce dynamic parameters, ' .
                ' such as starvation, garbage collection of dead units and finished battles, etc.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var DBCommon $db */
        $db = $this->getContainer()->get('db');


    }
}