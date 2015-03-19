<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

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
        $logged_in = $session->get('logged_in');
        $displayname = $session->get('displayname');

        if (!$logged_in) { $displayname = "stranger"; }

        return $this->render('GameBundle:Default:index.html.twig', array(
                'logged_in' => $logged_in,
                'displayname' => $displayname
         ));
    }
}