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
use GameBundle\Game\Rules\Rules;
use GameBundle\Game\Model\Clan;

class AdminController extends Controller
{

    public function indexAction()
    {
        return $this->render('GameBundle:AdminConsole:Admin.html.twig');
    }

    public function newgameAction() {
        $passphrase = $_POST['passphrase'];

        if ($passphrase == "restarttheworld")
        {
            // Get the services we need to invoke a new game
            $newgameService = $this->get('service_newgame');
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

        $rules = New Rules($db);
        $request['Action'] = 'Travel';
        $request['Issuer'] = new Clan(23);
        $request['Issuer']->setX(4);
        $request['Issuer']->setY(4);
        $request['Args'] = '4,5';
        $result = $rules->submit($request);
        print_r($result);
        die();
        return new RedirectResponse('/admin');
    }

}