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
use GameBundle\Game\Model\Mapzone;

class MapviewController extends Controller
{
    public function getMapAction()
    {
        $session = $this->get('session');
        $topleft = $session->get('mvid');

        $db = $this->get('db');
        $query = "SELECT * FROM mapzone WHERE id=" . $topleft . ";";
        $db->setQuery($query);
        $db->query();
        $cz = $db->loadObject();
        $query = "SELECT * FROM mapzone WHERE (x>=" .$cz->x. " AND x<=(" .$cz->x. "+11)) AND (y>=" .$cz->y. " AND y<=(" .$cz->y. "+4));";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();
        foreach ($rows as $row) {
            $row->x = $row->x - $cz->x;
            $row->y = $row->y - $cz->y;
        }

        $response = new Response();
        $response->setContent(json_encode(array(
            'data' => $rows
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function indexAction() {
        $session = $this->get('session');
        $session->set('mvid', 10);

        return $this->render('GameBundle:Game:mapview.html.twig');
    }

}