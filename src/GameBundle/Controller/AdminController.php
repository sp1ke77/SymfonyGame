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

use GameBundle\Game\Rules\EnforceParams;

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
            $newgameService = $this->get('service_newgame');
            $newgameService->creategame('yesanotherone','yesathirdone');

          // Set the game timer to start

            return new RedirectResponse('/admin');

            // Return Redirect to a page that shows the stats for the current scenario
        }
    }

    public function testAction()
    {
        $db = $this->get('db');

        $EnforceParams = new EnforceParams();
        $EnforceParams->setDb($db);
        $EnforceParams->trashDeadObjects();

        return new RedirectResponse('/admin');
    }

}