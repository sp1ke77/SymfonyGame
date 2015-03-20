<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/19/2015
 * Time: 7:55 PM
 *
 *  This will eventually be a wrapper for the scenario.json that is used to generate newgames.
 *  For now we're going to hardcode it.
 *
 */

namespace GameBundle\Services;
use GameBundle\Game\DBCommon;
use \Exception as Exception;

class GameworldScenarioService
{
    /**
     * @var DBCommon $db
     */

    protected $db;

    /**
     * @param DBCommon $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    public function randomizeMap()
    {
        // Create 1600 map zones and link them with a discrete x,y

        for ($i = 0; $i < 400; $i++)
        {
            // Create the mapzone

            $query = "INSERT INTO mapzone(geotype) VALUE (" . rand(1, 8) . ");";
            $this->db->setQuery($query);
            $this->db->query();

            $mapzoneId = $this->db->getLastInsertId();
            $x = $i % 20;
            $y = $i / 20;

            $query = "INSERT INTO map(x, y, mapzone) VALUE (" . $x . ", " . $y . ", " . $mapzoneId .");";
            $this->db->setQuery($query);
            $this->db->query();

        }
    }

    public function initializeCities()
    {
        $cities = array(
            'Ugarit' => array(
                'Description' => 'An ancient port of the Hurrians',
                'x' => 3,
                'y' => 8,
                'Region' => 'Alalakh',
                'God' => 'Tesub'),
            'Qadesh' => array(
                'Description' => '',
                'x' => 2,
                'y' => 3,
                'Region' => 'Amurru',
                'God' => ''),
            'Hamath' => array(
                'Description' => '',
                'x' => 5,
                'y' => 7,
                'Region' => 'Nuhasse',
                'God' => 'Astarte'),
            'Arvad' => array(
                'Description' => '',
                'x' => 5,
                'y' => 2,
                'Region' => 'Jazirat',
                'God' => ''),
            'Gubal' => array(
                'Description' => '',
                'x' => 15,
                'y' => 13,
                'Region' => '',
                'God' => 'Amun'),
            'Tyre' => array(
                'Description' => '',
                'x' => 12,
                'y' => 5,
                'Region' => '',
                'God' => 'Melkart'),
            'Shechem' => array(
                'Description' => '',
                'x' => 13,
                'y' => 12,
                'Region' => 'Nahal Iron',
                'God' => ''),
            'Megiddo' => array(
                'Description' => '',
                'x' => 17,
                'y' => 1,
                'Region' => 'Megiddo',
                'God' => ''),
            'Asqaluna' => array(
                'Description' => '',
                'x' => 15,
                'y' => 2,
                'Region' => 'Asqanu',
                'God' => 'El'),
            'Gezer' => array(
                'Description' => '',
                'x' => 6,
                'y' => 8,
                'Region' => 'Nahal Iron',
                'God' => 'Amun'),
            );

        foreach ($cities as $k => $v)
        {
            $cId = $this->createCity($k, $v['Description'], $v['x'], $v['y']);
            $this->createRegion($v['Region'], $v['God'], $cId);
        }
    }

    public function initializeTradeGoods()
    {


    }

    public function createSomeTribes()
    {


    }

    public function createSomeClans()
    {


    }

    /*

            PRIVATE FUNCTIONS

    */

    /**
     *
     * createCity
     *
     * @param string $name
     * @param string $desc
     * @return int
     */
    private function createCity($name, $desc, $x, $y)
    {
        $query = "INSERT INTO depot(wheat, olives, cattle) VALUES(200,100,100);";
        $this->db->setQuery($query);
        $this->db->query();
        $depotId = $this->db->getLastInsertId();

        $query = "INSERT INTO city(named, description, x, y, depot) VALUES('" . $name . "', '" . $desc . "', " . $x . ", " . $y . ", " . $depotId . ");";
        $this->db->setQuery($query);
        $this->db->query();
        return $this->db->getLastInsertId();
    }

    private function createRegion($named, $god, $cityId)
    {
        $query = "INSERT INTO region(named, god, city) VALUES('" . $named . "', '" . $god . "', " . $cityId . ");";
        $this->db->setQuery($query);
        $this->db->query();

    }
}
