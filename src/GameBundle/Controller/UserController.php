<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use GameBundle\Utils\FrontEndUtils;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\UserAccount;
use GameBundle\Game\Model\Factories\AgentFactory;
use GameBundle\Services\LoginService;
use GameBundle\Services\AgentService;

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
        $username = $_POST['username'];
        $password = $_POST['password'];
        $this->login($username, $password);
        return new RedirectResponse('/joingame');
    }

    /**
     * @return RedirectResponse
     */
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
        $loginService = $this->get('service_login');
        $frontEndUtils = new FrontEndUtils();

        // Better be exactly three arguments to that post
        $body = $frontEndUtils->parseQueryString($this->get('request')->getContent(), 3);

        if (empty($body))
        {
            // Malformed post; wrong number of parameters
            return new RedirectResponse('/error');
        } else {
            if ((!isset($body['username'])) || (!isset($body['password'])) || (!isset($body['email']))) {
                // Nope, malformed post; go away
                return new RedirectResponse('/error');
            }
            $loginService->createNewUser($body['username'], $body['password'], $body['email']);
            $this->login($body['username'], $body['password']);
            return new RedirectResponse('/joingame');
        }
    }

    function createCharacterAction()
    {
        /**
         * @var DBCommon $db
         * @var LoginService $loginService
         */
        $db = $this->get('db');
        $loginService = $this->get('service_login');
        $session = $this->get('session');

        // This is our post form; eventually we will move to an Ajax query
        $named = $_POST['name'];
        $culture = $_POST['culture'];
        $city = $_POST['city'];
        $allegiance = $_POST['liege'];

        if ($session->get('user_id') == null) {
            // No user fool
            return new RedirectResponse('/');
        } else {
            if ($loginService->getCharacterByUserID($session->get('user_id'))) {
                // You already have a character doofus
                return new RedirectResponse('/');
            } else {
                // Get our User Account out
                $userid = $session->get('user_id');
                $user = new UserAccount($userid);
                $user->setDb($db);
                $user->load();

                // Create a player character right quick
                $AgentFactory = new AgentFactory();
                $AgentFactory->setDb($db);
                $id = $AgentFactory->factory($user->getId(), $named, $culture, $city, $allegiance);
                $user->setCharacterid($id);
                $user->update();

                // Plunk that newly-created agent ID into the session
                $session->set('aid', $id);
                return new ReDirectResponse('/joingame');
            }
        }
    }

    function joinGameAction()
    {
        /**
         * @var LoginService $loginService
         * @var AgentService $agentService
         */
        $session = $this->get('session');
        $loginService = $this->get('service_login');
        $userid = $session->get('user_id');
        $agentid = $loginService->getCharacterByUserID($userid);
        if (!empty($agentid)) {
            $session->set('aid', $agentid);
            return new RedirectResponse($this->getMapviewRoute($agentid));
        } else {
            return $this->render('GameBundle:User:CharacterCreation.html.twig');
        }
    }

    /*
     *
     *
     *                              PRIVATE FUNCTIONS
     *
     *
     */

    /**
     * @param $username
     * @param $password
     * @return JsonResponse|RedirectResponse
     */
    private function login($username, $password)
    {
        /**
         * @var LoginService $loginService
         */
        $loginService = $this->get('service_login');
        $session = $this->get('session');
        $loginResults = $loginService->doLoginRequest($username, $password);
        if (empty($loginResults['user id'])) {
            return new RedirectResponse('/error');
        } else {
            $session->set('logged_in', true);
            $session->set('user_id', $loginResults["user id"]);
            $session->set('username', $loginResults["username"]);
            return new JsonResponse('{ "Result":"Success","Message:"Logged in as "' .$loginResults['username']. ' }');
        }
    }

    /**
     * @param $agentid
     * @return RedirectResponse
     */
    private function getMapviewRoute($agentid)
    {
        $session = $this->get('session');
        $agentService = $this->get('service_agent');
        $mvid = $agentService->getMapviewID($agentid);
        $session->set('mvid', $mvid);
        return '/mapview/' .$mvid;
    }
}