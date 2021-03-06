<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use GameBundle\Services\AgentService;
use GameBundle\Game\DBCommon;

/**
 * Class DefaultController
 * @package GameBundle\Controller
 */
class DefaultController extends Controller
{

    /**
     * Constructor creates a new session
     */
    public function __construct()
    {
        $this->Session = new Session();
        $this->Session->start();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $session = $this->get('session');
        $logged_in = $session->get('logged_in');
        $username = $session->get('username');

        $userid = null;

        if (!$logged_in) {
            $username = "stranger";
        } else {
            // Get the Agent id of the logged_in user
            $userid = $session->get('user_id');
        }

        // If said Agent id is numeric, assume a character exists
        if (is_numeric($userid))
        {
            return new RedirectResponse('/joingame');
        }

        return $this->render('GameBundle:Default:index.html.twig', array(
                'logged_in' => $logged_in,
                'username' => $username
         ));
    }
}