<?php

namespace GameBundle\Command\GameTimer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use GameBundle\Game\Rules\EnforceParams;

class EnforceParamsCommand extends ContainerAwareCommand
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
        /** @var $logger LoggerInterface */
        $logger = $this->getContainer()->get('logger');
        $db = $this->getContainer()->get('db');

        echo 'EnforceParams round begun ... ';
        $logger->info('EnforceParams round begun ... ');

        $EnforceParams = new EnforceParams();
        $EnforceParams->setDb($db);

        $EnforceParams->enforce();
        $logger->info($EnforceParams->getStatus());

        echo 'EnforceParams round complete';
        $logger->info('EnforceParams round complete');
    }
}