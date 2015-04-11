<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/19/2015
 * Time: 4:29 PM
 */
namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

// Temporary


class AdminController extends Controller
{

    public function indexAction()
    {
        return $this->render('GameBundle:AdminConsole:Admin.html.twig');
    }

    public function testAction()
    {
        $map = $this->get('service_map');
        $mapzones = $map->getMapObjectsByViewport('city', 1725, 11, 5);
        echo '<pre>';
        print_r($mapzones);
        die();
        return new RedirectResponse('/admin');
    }

}