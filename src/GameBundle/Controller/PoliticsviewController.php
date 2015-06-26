<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use GameBundle\Utils\FrontEndUtils;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\Agent;
use GameBundle\Game\Model\City;

class PoliticsviewController extends Controller
{

    function indexAction()
    {
        $pig = true;
        return $this->render('GameBundle:Game:politicsview.html.twig', array(
            'aPictureofaPig' => $pig
        ));
    }
}