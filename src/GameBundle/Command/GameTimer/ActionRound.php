<?php

namespace GameBundle\Command\GameTimer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GameBundle\Game\DBCommon;

class ActionRound extends ContainerAwareCommand
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
        /** @var DBCommon $db */
        $db = $this->getContainer()->get('db');


    }
}