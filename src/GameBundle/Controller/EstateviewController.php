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

class EstateviewController extends Controller
{
    function indexAction()
    {
        $db = $this->get('db');
        $session = $this->get('session');
        $aid = $session->get('aid');
        $player = new Agent($aid);
        $player->setDb($db);
        $player->load();
        $cid = $player->getCity();
        $city = new City($cid);
        $city->setDb($db);
        $city->load();

        $myName = $player->getNamed();
        $myCoin = $player->getCoin();
        $myCity = $city->getNamed();
        $myTradeIncome = $city->getTradeincome(); // City's property reflects tomorrow's income from markets
        $myTotalIncome = $myTradeIncome;

        $myBuildingCosts = 0;
        $myTotalCosts = $myBuildingCosts;

        $myTotal = $myCoin + ceil($myTotalIncome) - $myTotalCosts;

        $myBuildings = [];

        return $this->render('GameBundle:Game:estateview.html.twig', array(
            'myName' => $myName,
            'myCoin' => $myCoin,
            'myCity' => $myCity,
            'myTradeIncome' => $myTradeIncome,
            'myBuildingCosts' => $myBuildingCosts,
            'myTotal' => $myTotal,
            'myBuildings' => $myBuildings
        ));
    }
}