<?php

namespace GameBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Scenario\Newgame;

class NewgameCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('Newgame:initialize')
            ->setDescription('Launch the master process for trashing the current gameworld and instantiating a new one.')
           ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getContainer()->get('logger');

        $logger->info('Creating the world ... ');

        if ($input->getArgument('passphrase') == 'restarttheworld')
        {
            $newgame = $this->getContainer()->get('newgame');
            $newgame->createGame();
        }

        $logger->info('Finished initializing new game! ');
    }
}