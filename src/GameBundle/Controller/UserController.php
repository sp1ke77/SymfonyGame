<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\Agent;
use GameBundle\Game\Model\UserAccount;
use GameBundle\Game\Model\Factories\AgentFactory;

/**
 * Class UserController
 * @package GameBundle\Controller
 */
class UserController extends Controller
{
    /**
     * @return RedirectResponse
     */
    function loginAction()
    {
        /** @var DBCommon $db */
        $loginService = $this->get('service_login');
        $session = $this->get('session');

        $userName = $_POST['username'];
        $password = $_POST['password'];

        $db = $this->get('db');
        $loginService->setDb($db);

        $results = $loginService->doLoginRequest($userName, $password);

        if (!isset($results)) {
            return new RedirectResponse('/registration');
        } else {
            $session->set('logged_in', true);
            $session->set('user_id', $results["user id"]);
            $session->set('username', $results["username"]);
            return new RedirectResponse('/');
        }
    }

    function logoutAction()
    {
        $session = $this->get('session');
        $session->clear();

        return new RedirectResponse('/');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function registrationAction()
    {
        return $this->render('GameBundle:User:NewUser.html.twig');
    }

    /**
     * @return JsonResponse
     */
    function submitRegistrationAction()
    {
        $session = $this->get('session');
        $loginService = $this->get("service_login");
        $request = $this->get('request');
        $content = $request->getContent();

        // All this nonsense is gonna go into LoginService as a function called
        // submitRegFormByQString(), in order to accomodate future expansion to the
        // input security and sanitation system
        $contents = explode('&', $content);
        if (count($contents) != 3)
        {
            // Eventually we will log each time this happens and ban IPs that regularly
            // submit requests with the wrong number of parameters
            return new RedirectResponse('/error');
        }

        $username = explode('=', $contents[0])[1];
        $email = explode('=', $contents[1])[1];
        $password = explode('=', $contents[2])[1];

        $loginService->createNewUser($username, $password, $email);
        $results = $loginService->doLoginRequest($username, $password);

        if ($results["logged in"] == true) {
            $session->set('logged_in', true);
            $session->set('user_id', $results["user id"]);
            return new JsonResponse(json_encode('{"Result":"Successfully logged in as ' . $username . '"}'));
            // Check for a character and redirect to either character creation or mapview
        } else {
            return new RedirectResponse('/error');
        }
    }

    function characterOverviewAction()
    {
        /** @var DBCommon $db */
        $db = $this->get('db');
        $session = $this->get('session');
        $loginService = $this->get('service_login');
        $agentService = $this->get('service_agent');

        $userid = $session->get('user_id');
        $agentid = $loginService->checkForCharacter($userid);

        if (!empty($agentid)) {
            $session->set('aid', $agentid);
            $mvid = $agentService->getMapviewID($agentid);
            $session->set('mvid', $mvid);

            // Find and set the session->mvid property -- the topleft tile to display

            return $this->render('GameBundle:Game:mapview_overview.html.twig');
        } else {
            return $this->render('GameBundle:User:CharacterCreation.html.twig');
        }

        // If the player is logged in, output the character details or, in the case of no character
        // extant for the user currently logged in, a link to the character creation process
    }

    function createCharacterAction()
    {
        /** @var DBCommon $db */
        $db = $this->get('db');
        $session = $this->get('session');

        $named = $_POST['name'];
        $culture = $_POST['culture'];
        $city = $_POST['city'];
        $allegiance = $_POST['liege'];

        $userid = $session->get('user_id');
        $user = new UserAccount($userid);
        $user->setDb($db);
        $user->load();

        $AgentFactory = new AgentFactory();
        $AgentFactory->setDb($db);
        $id = $AgentFactory->factory($user->getId(), $named, $culture, $city, $allegiance);
        $session->set('aid', $id);
        return new RedirectResponse('/character');

    }
}