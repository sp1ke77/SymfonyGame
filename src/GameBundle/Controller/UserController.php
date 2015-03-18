<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use GameBundle\Services\LoginService;
use GameBundle\Game\DBCommon;

class UserController extends Controller
{

    function loginAction()
    {

        /** @var DBCommon $db */
        $db = $this->get('db');
        $userName = $_POST['username'];
        $password = $_POST['password'];

        $loginService = new LoginService();
        $loginService->setDb($db);

        $user = $loginService->doLoginRequest($userName, $password);

        if (empty($user)) {
            return new RedirectResponse('/');
        } else {
            $userId = $user->user_id;
            $name = $user->name;
            return new RedirectResponse('/');
        }
    }
}