<?php

namespace GameBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Scenario\Newgame;

/**
 * Class ActionRoundCommand
 * @package GameBundle\Command\GameTimer
 */
class NewgameCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('Newgame:initialize')
            ->setDescription('Launch the master process for trashing the current gameworld and instantiating a new one.')
            ->addArgument(
                'passphrase',
                InputArgument::OPTIONAL,
                'What is the passphrase?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getContainer()->get('logger');

        $logger->info('Creating the world ... ');

        if ($input->getArgument('passphrase') == 'restarttheworld')
        {
            $db = $this->getContainer()->get('db');
            $newgame = new Newgame();
            $newgame->setDb($db);
            $newgame->createGame();
        }

        $logger->info('Finished initializing new game! ');
    }
}