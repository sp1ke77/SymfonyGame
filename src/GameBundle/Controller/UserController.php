<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use GameBundle\Services\LoginService;
use GameBundle\Game\DBCommon;
use \Exception as Exception;


class UserController extends Controller
{

    function loginAction()
    {
        /** @var DBCommon $db */
        $loginService = $this->get('service_login');
        $session = $this->get('session');

        $userName = $_POST['username'];
        $password = $_POST['password'];

        $db = $this->get('db');
        $loginService->setDb($db);

        $userObj = $loginService->doLoginRequest($userName, $password);

        if (!isset($userObj)) {
            return new RedirectResponse('/registration');
        } else {
            $session->set('user_id', $userObj->user_id);
            $session->set('name', $userObj->name);
            return new RedirectResponse('/');
        }
    }

    function registrationAction()
    {
        return $this->render('GameBundle:User:NewUser.html.twig');
    }

    function submitRegistrationAction()
    {
        $username = $_POST['username'];
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];
        $dateofBirth = $_POST['dateofbirth'];
        $email = $_POST['email'];

        $db = $this->get('db');
        $session = $this->get('session');

        $query = 'SELECT * FROM game.user WHERE username="' . $username . '";';
        $db->setQuery($query);
        $userObj = $db->loadObject();

        if (!empty($userObj)) { throw new Exception('Your username must be unique'); }

        if ($password1 != $password2) { throw new Exception('Passwords do not match'); }

            $query = 'INSERT INTO
                          game.user
                          (username, password, email, date_of_birth)
                      VALUES("' . $username . '", "' . $password1 . '", "'
                            . $dateofBirth . '",  "' . $email . '");';
            $db->setQuery($query);
            $db->query();
            $user = $db->loadObject();

            $session->set('logged_in', true);
            $session->set('user_id', $user->user_id);
            $session->set('username', $user->name);

        return new RedirectResponse('/');
    }
}