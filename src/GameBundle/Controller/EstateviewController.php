<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use GameBundle\Utils\FrontEndUtils;
use GameBundle\Game\DBCommon;
use GameBundle\Services\AgentService;

class EstateviewController extends Controller
{

    function indexAction()
    {
        $mySkim = round(0.122223, 2);
        $myTotalIncome = $mySkim;

        $myBuildingCosts = 2.2;
        $myTotalCosts = $myBuildingCosts;

        $myTotal = $myTotalIncome - $myTotalCosts;

        $myBuildings = [];

        return $this->render('GameBundle:Game:estateview.html.twig', array(
            'mySkim' => $mySkim,
            'myBuildingCosts' => $myBuildingCosts,
            'myTotal' => $myTotal,
            'myBuildings' => $myBuildings
        ));
    }
}