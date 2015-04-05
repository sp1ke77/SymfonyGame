<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/19/2015
 * Time: 4:29 PM
 */
namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

// Temporary
use GameBundle\Game\Simulation\RandomEvents\RandomEvents;
use GameBundle\Game\Simulation\ActionRound;
use GameBundle\Game\DBCommon;

class AdminController extends Controller
{

    public function indexAction()
    {
        return $this->render('GameBundle:AdminConsole:Admin.html.twig');
    }

    public function newgameAction() {

        $db = $this->get('db');
        $passphrase = $_POST['passphrase'];

        if ($passphrase == "restarttheworld")
        {
            // Get the services we need to invoke a new game
            $newgameService = $this->get('service_newgame');
            $newgameService->setDb($db);
            $path = $this->get('kernel')->getRootDir();

            // Configure NewgameService and execute createGame()
            $newgameService->setPath($path);
            $newgameService->createGame('yesanotherone','yesathirdone');

          // Set the game timer to start

            return new RedirectResponse('/admin');

            // Return Redirect to a page that shows the stats for the current scenario
        }
    }

    public function testAction()
    {
        $db = $this->get('db');
        // Swap this whatever service is to be tested

        $actionround = new ActionRound();
        $actionround->setDb($db);
        $actionround->execute();

        return new RedirectResponse('/admin');
    }

}