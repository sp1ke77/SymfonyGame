<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{

    public function __construct()
    {
        $this->Session = new Session();
        $this->Session->start();
    }

    public function indexAction()
    {
        $session = $this->get('session');
        if ($session->get('logged_in'))
        {
            $loggedIn = true;
            $userName = $session->get('user_name');
        } else {
            $loggedIn = false;
            $userName = "stranger";
        }

        return $this->render('GameBundle:Default:index.html.twig', array(
                'loggedIn' => $loggedIn,
                'user_name' => $userName
         ));
    }
}