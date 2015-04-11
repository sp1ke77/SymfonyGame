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
use GameBundle\Game\Model\Clan;

class AdminController extends Controller
{

    public function indexAction()
    {
        return $this->render('GameBundle:AdminConsole:Admin.html.twig');
    }

    public function testAction()
    {
        $db = $this->get('db');
        $rules = $this->get('service_rules');
        $clan = new Clan(15);
        $clan->setDb($db);
        $clan->load();
        $request = $rules->createRequest($clan, 'holiday', '4,5');
        $output = $rules->submit($request);
        echo '<pre>';
        print_r($output);
        die();
        return new RedirectResponse('/admin');
    }

}