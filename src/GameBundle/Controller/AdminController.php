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
use GameBundle\Game\Simulation\AI\Clans\Behavior;
use GameBundle\Game\Model\Depot;
use GameBundle\Game\Model\Clan;
use GameBundle\Game\Model\TradegoodPlatonic;
use GameBundle\Game\Rules\Rules;

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


        /*$actionround = new ActionRound();
        $actionround->setDb($db);
        $actionround->execute();*/

        $behavior = new Behavior($db);
        $rules = new Rules();
        $rules->setDb($db);
        $clan = new Clan(15);
        $clan->setDb($db);
        $clan->load();

        $depot = new Depot($clan->getDepot());
        $depot->setDb($db);
        $depot->load();

        $output = "";
        $possessions = $depot->Assess();
        foreach ($possessions as $possession) {
            if ($possession->getTgtype() == 'food') {
                $amt = $depot->{strtolower($possession->getNamed())};
                $request = $rules->createRequest($clan, 'sell goods', $possession->getId().','.$amt);
                $output .= print_r($rules->submit($request)).' +++++ ';
            }
        }
        var_dump($output);
        die();
        return new RedirectResponse('/admin');
    }

}