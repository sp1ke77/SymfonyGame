<?php

namespace GameBundle\Command\GameTimer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;

class ActionRoundCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('GameTimer:ActionRound')
            ->setDescription('Recurs every 10 minutes to process action queues on the part of characters, ' .
                                'agents, units and clans.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        /** @var $logger LoggerInterface */
        $logger = $this->getContainer()->get('logger');
        $logger->info('ActionRound begun ... ');

        $logger->info('ActionRound complete ... ');

    }
}