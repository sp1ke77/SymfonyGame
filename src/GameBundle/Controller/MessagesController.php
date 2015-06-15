<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session;

/**
 * Class MessagesController
 * @package GameBundle\Controller
 */
class MessagesController extends Controller
{

    function errorAction()
    {
        return $this->render('GameBundle:Messages:Error.html.twig');
    }
}