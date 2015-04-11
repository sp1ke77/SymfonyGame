<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/25/2015
 * Time: 3:24 PM
 */

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GameBundle\Game\Model\Mapzone;

class MapviewController extends Controller
{
    public function indexAction($slug) {
        $session = $this->get('session');
        if (is_numeric($slug)){
            $session->set('mvid', $slug);
        } else {
            // Nope
        }
        return $this->render('GameBundle:Game:mapview.html.twig');
    }

    public function getMapAction()
    {
        $map = $this->get('service_map');
        $session = $this->get('session');
        $topleft = $session->get('mvid');
        if (is_numeric($topleft)) {
            $mapzones = $map->getMapObjectsByViewport('mapzone', $topleft, 11, 5);
            $cities = $map->getMapObjectsByViewport('city', $topleft, 11, 5);

            $response = new Response();
            $response->setContent(json_encode(array(
                'mapzones' => $mapzones,
                'cities' => $cities
            )));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    public function getEntitiesAction()
    {
        $map = $this->get('service_map');
        $session = $this->get('session');
        $topleft = $session->get('mvid');
        if (is_numeric($topleft)) {
            $clans = $map->getMapObjectsByViewport('clan', $topleft, 22, 10);

            $response = new Response();
            $response->setContent(json_encode(array(
                'clans' => $clans
            )));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    public function getNewsAction()
    {
        $map = $this->get('service_map');
        $session = $this->get('session');
        $topleft = $session->get('mvid');
        if (is_numeric($topleft)) {
            $news = $map->getMapObjectsByViewport('news', $topleft, 22, 10, 4);

            $response = new Response();
            $response->setContent(json_encode(array(
                'news' => $news
            )));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

}