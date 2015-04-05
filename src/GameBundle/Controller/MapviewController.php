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
        $db = $this->get('db');
        $session = $this->get('session');
        $topleft = $session->get('mvid');
        if (is_numeric($topleft)) {
            $query = "SELECT * FROM mapzone WHERE id=" . $topleft . ";";
            $db->setQuery($query);
            $db->query();
            $cz = $db->loadObject();
            $query = "SELECT * FROM mapzone WHERE (x>=" . $cz->x . " AND x<=(" . $cz->x . "+11)) AND (y>=" . $cz->y . " AND y<=(" . $cz->y . "+5));";
            $db->setQuery($query);
            $db->query();
            $mapzones = $db->loadObjectList();
            foreach ($mapzones as $row) {
                $row->x = $row->x - $cz->x;
                $row->y = $row->y - $cz->y;
            }
            $query = "SELECT * FROM city WHERE (x>=" . $cz->x . " AND x<=(" . $cz->x . "+11)) AND (y>=" . $cz->y . " AND y<=(" . $cz->y . "+5));";
            $db->setQuery($query);
            $db->query();
            $cities = $db->loadObjectList();
            foreach ($cities as $row) {
                $row->x = $row->x - $cz->x;
                $row->y = $row->y - $cz->y;
            }

            $response = new Response();
            $response->setContent(json_encode(array(
                'data' => $mapzones,
                'cities' => $cities
            )));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } else {
            // Nope
        }
    }

    public function getEntitiesAction()
    {
        $db = $this->get('db');
        $session = $this->get('session');
        $topleft = $session->get('mvid');
        if (is_numeric($topleft)) {
            $query = "SELECT * FROM mapzone WHERE id=" . $topleft . ";";
            $db->setQuery($query);
            $db->query();
            $cz = $db->loadObject();
            $query = "SELECT * FROM clan WHERE (x>=" . $cz->x . " AND x<=(" . $cz->x . "+11)) AND (y>=" . $cz->y . " AND y<=(" . $cz->y . "+5));";
            $db->setQuery($query);
            $db->query();
            $clans = $db->loadObjectList();
            foreach ($clans as $row) {
                $row->x = $row->x - $cz->x;
                $row->y = $row->y - $cz->y;

            }

            $response = new Response();
            $response->setContent(json_encode(array(
                'clans' => $clans
            )));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } else {
            // Nope
        }
    }

    public function getNewsAction()
    {
        $db = $this->get('db');
        $session = $this->get('session');
        $topleft = $session->get('mvid');
        if (is_numeric($topleft)) {
            $query = "SELECT * FROM mapzone WHERE id=" . $topleft . ";";
            $db->setQuery($query);
            $db->query();
            $cz = $db->loadObject();
            $query = "SELECT * FROM news WHERE (x>=" . $cz->x . " AND x<=(" . $cz->x . "+22)) AND (y>=" . $cz->y . " AND y<=(" . $cz->y . "+10)) ORDER BY dated ASC LIMIT 4;";
            $db->setQuery($query);
            $db->query();
            $news = $db->loadObjectList();
            foreach ($news as $row) {
                $row->x = $row->x - $cz->x;
                $row->y = $row->y - $cz->y;
            }

            $response = new Response();
            $response->setContent(json_encode(array(
                'news' => $news
            )));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } else {
            // Nope
        }
    }

}