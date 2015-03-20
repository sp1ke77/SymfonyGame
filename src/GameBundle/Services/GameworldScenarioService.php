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
use Symfony\Component\Form\Guess\ValueGuess;

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
        // Create 400 map zones, randomize geotype and link each with an abstract x,y
        for ($i = 0; $i < 400; $i++)
        {
            // Create the mapzone

            $x = $i % 20;
            $y = $i / 20;

            $query = "INSERT INTO map(x, y, geotype) VALUE (" . $x . ", " . $y . ", " . rand(1, 8) .");";
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
        $tradegoods = array(
            'Wheat' => array(
                'Description' => 'The staff of life without which everyone starves',
                'Trade Value' => 1,
                'Food Value' => 1,
                'Type' => 'food'
            ),
            'Olives' => array(
                'Description' => 'As well as being tasty, an important source of oil and therefore crucial to the baker\'s trade.',
                'Trade Value' => 1.2,
                'Food Value' => 1.2,
                'Type' => 1
            ),
            'Cattle' => array(
                'Description' => 'The favorite pets of the rich and powerful, often their favorite totems and occasionally their favorite meals.',
                'Trade Value' => 3.8,
                'Food Value' => 2.8,
                'Type' => 1
            ),
            'Fish' => array(
                'Description' => 'Along with wheat, the mainstay of the local diet.',
                'Trade Value' => 1.2,
                'Food Value' => 1.2,
                'Type' => 1
            ),
            'Wood' => array(
                'Description' => 'Good lumber is needed to make the largest, most impressive houses as well as that princely symbol, the seagoing barque.',
                'Trade Value' => 4.8,
                'Food Value' => 0,
                'Type' => 2
            ),
            'Linen' => array(
                'Description' => 'Linen is needed for making clothing and housewares. Princes search far and wide for weavers of quality cloth.',
                'Trade Value' => 2.6,
                'Food Value' => 0,
                'Type' => 2
            ),
            'Gold' => array(
                'Description' => 'Gold is used to make the likenesses of gods and the honored dead. They say that to be cast this way makes one immortal.',
                'Trade Value' => 6.0,
                'Food Value' => 0,
                'Type' => 4
            ),
            'Copper' => array(
                'Description' => 'Copper is an exceptionally easy metal to work. As such it is used for everything: cookware, urns, hunting weapons.',
                'Trade Value' => 3.0,
                'Food Value' => 0,
                'Type' => 2
            ),
            'Incense' => array(
                'Description' => 'Required in all sacred spaces, incense is burnt to please the spirits of the holy houses and the tombs.',
                'Trade Value' => 4.5,
                'Food Value' => 0,
                'Type' => 4
            ),
            'Dyes' => array(
                'Description' => 'Nobles of all lands need bright dyes to color their clothes and paint the likenesses on their shrines.',
                'Trade Value' => 3.4,
                'Food Value' => 0,
                'Type' => 3
            )
        );

        foreach ($tradegoods as $k => $v)
        {
            $this->createTradeGood($k, $v['Description'], $v['Trade Value'], $v['Food Value'], $v['Type']);
        }
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
     * @param x
     * @param y
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

    private function createTradeGood($named, $description, $tv, $fv, $tgtype)
    {
        $query = "INSERT INTO tradegood(named, description, tradevalue, foodvalue, tgtype) VALUES('" . $named . "','" . $description . "'," . $tv . "," . $fv . "," . $tgtype . ");";

        $this->db->setQuery($query);
        $this->db->query();
    }
}
