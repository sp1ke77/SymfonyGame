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
            $gameworldAdminService = $this->get('service_gameworld_admin');
            $gameworldAdminService->trashGameworldRecords();
            $gameworldAdminService->setupGameworldRecords();

            $gameworldScenarioService = $this->get('service_gameworld_scenario');
            $gameworldScenarioService->randomizeMap();

          //
          // Populate the required tables from scenario data
          //
          // Set the game timer to start

            return new RedirectResponse('/admin');

            // Return Redirect to a page that shows the stats for the current scenario
        }
    }

}