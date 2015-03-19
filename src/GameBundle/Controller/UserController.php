<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use GameBundle\Game\DBCommon;
use GameBundle\Game\User;


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
            $session->set('displayname', $results["display name"]);
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
     * @return RedirectResponse
     * @throws \Exception
     */
    function submitRegistrationAction()
    {
        $username = $_POST['username'];
        $name = $_POST['displayname'];
        $password = $_POST['password1'];
        $dateofBirth = $_POST['dateofbirth'];
        $email = $_POST['email'];

        /** @var DBCommon $db */
        $db = $this->get('db');

        $user = new User(null);
        $user->setDb($db);
        $user->setUsername($username);
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setDateofbirth($dateofBirth);
        $user->insert();

        return new RedirectResponse('/');
    }
}