<?php


namespace GameBundle\Controller;

use GameBundle\Game\Model\Persona;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session;
use GameBundle\Game\Model\Agent;
use GameBundle\Game\Model\City;

class CharacterviewController extends Controller
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
        $persona = new Persona($player->getPersona());
        $persona->setDb($db);
        $persona->load();

        $player->setDb(null);
        $player->setIsplayer(null);
        $player->setPtype(null);

        return $this->render('GameBundle:Game:characterview.html.twig', array(
            'myCity' => $city,
            'myCharacter' => $player,
            'myReputation' => $persona,
        ));
    }
}