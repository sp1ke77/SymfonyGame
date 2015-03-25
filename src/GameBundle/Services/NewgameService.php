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
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\Tribe;
use GameBundle\Services\MapService;

/**
 * Class NewgameService
 * @package GameBundle\Services
 */
class NewgameService
{
    /**
     * @var $db DBCommon
     */
    protected $db;

    protected $path;

    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @param DBCommon $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     *  createGame()
     *
     *  Reset the entire gameworld, dropping and recreating all the DB tables
     *  and populating them procedurally from object literals. Later these
     *  should be abstracted out to a json file.
     *
     *
     * @param string $password
     * @param string $password2
     */
    public function createGame($password, $password2)
    {
        if ($password == "yesanotherone" && $password2 == "yesathirdone")
        {
            $this->trashGameworldTables();
            $this->setupGameworldTables();

            $this->randomizeMap();

            $this->initializeCities();
            $this->initializeTradeGoods();

            // $this->createTradeGoodTokens();
            $this->createSomeTribes();
            $this->createSomeClans();
        }
    }

    /*
     *
     *             PROTECTED FUNCTIONS
     *
     *
     */

    /**
     *  randomizeMap()
     *
     *  This will eventually take a param $mapsize, which must be a perfect square int, and base its iteration on
     *  that bounds. Rows and columns will be equal to square_root($mapsize).
     *
     */
    protected function randomizeMap()
    {
        // Create 400 map zones, randomize geotype and link each with an abstract x,y
        for ($i = 0; $i < 400; $i++)
        {
            // Create the mapzone

            $x = $i % 20;
            $y = $i / 20;

            $query = "INSERT INTO mapzone(x, y, geotype) VALUE (" . $x . ", " . $y . ", " . rand(1, 8) .");";
            $this->db->setQuery($query);
            $this->db->query();

        }
    }

    /**
     *  Object literal must be abstracted to a json
     *
     */
    protected function initializeCities()
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

    /**
     *  Object literal must be abstracted to a json
     *
     */
    protected function initializeTradeGoods()
    {
        // CREATE THE PLATONIC TRADE GOODS

        // Decode the json document and spit it out as an associative array
        $file = file_get_contents($this->path . "/Resources/Scenario/tradegoods.json");
        $tradegoods[] = json_decode($file, true);
        for ($i = 0; $i < 40; $i++)
        {
            // Get a random key from the array (nesting depth one)
            $rk = array_rand($tradegoods[0]);
            $tradegood = $tradegoods[0][$rk];   // Pull the specific object

            // Get the data from the random tradegood
            $name = $tradegood['Name'];
            $description = $tradegood['Description'];
            $fv = $tradegood['Food Value'];
            $tv = $tradegood['Trade Value'];
            $tgtype = $tradegood['Type'];

            // Insert into game.tradegood
            $this->createTradeGood($name, $description, $fv, $tv, $tgtype);
        }
    }

    /**
     *  Object literal must be abstracted to a json
     *
     *  Will eventually take a parameter $number to use as the condition for the iteration
     *
     */
    protected function createSomeTribes()
    {
        $tribes = array(
            'Canaanite' => array("Arsai", "Baalat", "Eshmun", "Wakhasir", "Lotan",
                "Margod", "Mawat", "Melwart", "Nikkal", "Shalim", "Shachar",
                "Qadeshtu", "Yarikh", "Yaw"),
            'Hurrian' => array("Matarum", "Yariha-amu", "Ihid", "Iniru", "Sin-mussalim", "Abbana-el",
                "Yaiti-obal", "Yimsi-el", "Mut-rame", "Habdu-ami", "Kabi-epuh",
                "Zakija-Hamu", "Zunan"),
            'Luwian' => array("Maddunani", "Kuruntiya", "Runtiya", "Hantawati", "Tarkasna", "Zupari",
                "Tupana", "Tuwarsanza", "Tibe", "Esi-tmmata", "Manaha", "Umanaddu",
                "Musisipa"),
            'Tejenu' => array("Andronek", "Etewokewet", "Filaretos", "Kleonak", "Nikostros", "Tros",
                "Khalkeos", "Xaridhmos", "Kaliod", "Kupirijo", "Radamanq", "Makhawon",
                "Glaukos"),
            'Keftiu' => array('Tinay', '', '', '', '', ''),
            'Amurru' => array("Yarikhu", "Rabbu", "Uprapu", "Yakhruru", "Mikhalizayu", "Almutu",
                "Numkha", "Aqba-el", "Yamutbal", "Ya'ilanu", "Sim'alites", "Amnanu",
                "Zamri-Lim"),
            'Shasu' => array("Jetheth", "Oholibamah", "Mibzar", "Iram-Ammon", "Kenaz", "Pinon",
                "Timnah", "Magdiel", "Elah", "Zepho", "Kenaz", "Mizzah", "Nathath")
        );

        for ($i = 1; $i < 32; $i++)
        {
            // Pick a culture group
            $cg = array_rand($tribes);
            $names = $tribes[$cg];

            // Pick a name from the ones enumerated
            $chooseOne = rand(0, (count($names) - 1));
            $name = $names[$chooseOne];
            $this->createTribe($name, $cg);
        }
    }

    /**
     * Create  one clan per tribe and that's it
     *
     */
    protected function createSomeClans()
    {
        $tribes = $this->getAllTribes();

        foreach ($tribes as $tribe)
        {
            $tid = $tribe->getTribeId();
            $this->createClan($tid);
        }
    }

    /*
     *
     *
     *          PRIVATE FUNCTIONS
     *
     *
     */

    /**
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
        $depotId = $this->createDepot();
        $query = "INSERT INTO city(named, description, x, y, depot) VALUES('" . $name . "', '" . $desc . "', " . $x . ", " . $y . ", " . $depotId . ");";
        $this->db->setQuery($query);
        $this->db->query();
        return $this->db->getLastInsertId();
    }

    /**
     * @param $named
     * @param $god
     * @param $cityId
     */
    private function createRegion($named, $god, $cityId)
    {
        $query = "INSERT INTO region(named, god, city) VALUES('" . $named . "', '" . $god . "', " . $cityId . ");";
        $this->db->setQuery($query);
        $this->db->query();

    }

    /**
     *
     * INSERT INTO game.tradegood
     *
     * @param int $mapzone
     * @param string $named
     * @param string $description
     * @param float $tv
     * @param float $fv
     * @param int $tgtype
     */
    private function createTradeGood($named, $description, $tv, $fv, $tgtype)
    {
        $query = "INSERT INTO tradegood_platonic(named, description, tradevalue, foodvalue, tgtype) VALUES('" . $named . "','" . $description . "'," . $tv . "," . $fv . "," . $tgtype . ");";
        $this->db->setQuery($query);
        $this->db->query();
    }

    /**
     *
     * INSERT INTO game.tribe
     *
     * @param $named
     * @param $culturegroup
     */
    private function createTribe($named, $culturegroup)
    {
        $query = "INSERT INTO tribe(named, culture) VALUES('" . $named . "', '" . $culturegroup . "');";
        $this->db->setQuery($query);
        $this->db->query();
    }

    /**
     * INSERT INTO game.clan
     *
     * Must have a valid tribeId to create a clan
     *
     * @param $tribeId
     */
    private function createClan($tribeId)
    {
        $depotId = $this->createDepot();
        $query = "INSERT INTO clan(tribe, depot, population, fighters, food, coin) VALUES(" . $tribeId . ", " . $depotId . ", 100, 60, 35, 0);";
        $this->db->setQuery($query);
        $this->db->query();
    }

    /**
     * INSERT INTO game.depot
     *
     * @return int the game.depot record id for the newly-created object
     */
    private function createDepot()
    {
        $query = "INSERT INTO depot(wheat, olives, cattle) VALUES(0,0,0);";
        $this->db->setQuery($query);
        $this->db->query();
        $depotId = $this->db->getLastInsertId();
        return $depotId;
    }

    /**
     * getAllTribes()
     *
     * Outputs an array of packaged Tribe objects representing all the tribes currently found in the db.
     *
     * @return array
     */
    function getAllTribes()
    {
        $tribes = [];

        $query = "SELECT id FROM tribe;";
        $this->db->setQuery($query);
        $objRows = $this->db->loadObjectList();

        foreach ($objRows as $objRow)
        {
            $tribe = new Tribe($objRow->id);
            $tribe->setDb($this->db);
            $tribe->load();
            array_push($tribes, $tribe);
        }

        return $tribes;
    }

    /**
     *  trashGameworldTables()
     *
     *  Drop all the gameworld tables in the database 'game'.
     *
     */
    private function trashGameworldTables()
    {
        $query = "USE game;";
        $this->db->setquery($query);
        $this->db->query();

        $query = "DROP TABLE mapzone;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE kingdom;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE city;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE region;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE tradegood;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE tribe;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE playercharacter;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE clan;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE building;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE unit;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE depot;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE diplomatic_relation;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE diplomatic_status;";
        $this->db->setQuery($query);
        $this->db->query();

    }

    /**
     *
     */
    private function setupGameworldTables()
    {
        // Create all nencessary tables
        $query = "CREATE TABLE game.mapzone (
                          id INT NOT NULL AUTO_INCREMENT,
                          x INT(2) NOT NULL,
                          y INT(2) NOT NULL,
                          geotype ENUM('plains','hills','mountains','desert','swamp','forest','shallowsea','deepsea') NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.kingdom (
                          id INT NOT NULL AUTO_INCREMENT,
                          imglarge VARCHAR(45) NULL,
                          imgsmall VARCHAR(45) NULL,
                          imgface VARCHAR(45) NULL,
                          dynasty VARCHAR(45) NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.city (
                          id INT NOT NULL AUTO_INCREMENT,
                          named VARCHAR(45) NULL,
                          imglarge VARCHAR(45) NULL,
                          imgsmall VARCHAR(45) NULL,
                          description VARCHAR(160) NULL,
                          depot INT NULL,
                          king INT NULL,
                          priest INT NULL,
                          x int NULL,
                          y int NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.region (
                          id INT NOT NULL AUTO_INCREMENT,
                          named VARCHAR(45) NULL,
                          imglarge VARCHAR(45) NULL,
                          imgsmall VARCHAR(45) NULL,
                          god VARCHAR(45) NULL,
                          city INT NULL,
                          ruledby INT NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.tradegood_platonic (
                          id INT NOT NULL AUTO_INCREMENT,
                          named VARCHAR(45) NULL,
                          imgfull VARCHAR(45) NULL,
                          description VARCHAR(160) NULL,
                          tradevalue NUMERIC(2,1) NULL,
                          foodvalue NUMERIC(2,1) NULL,
                          tgtype ENUM('food','supplies','goods','gifts') NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.tradegood_token (
                          id INT NOT NULL AUTO_INCREMENT,
                          mapzone INT NULL,
                          tg VARCHAR(45) NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.tribe (
                          id INT NOT NULL AUTO_INCREMENT,
                          named VARCHAR(45) NULL,
                          gregariousness NUMERIC(3,2) DEFAULT 0,
                          belligerence NUMERIC(3,2) DEFAULT 0,
                          tenacity NUMERIC(3,2) DEFAULT 0,
                          insularity NUMERIC(3,2) DEFAULT 0,
                          spirituality NUMERIC(3,2) DEFAULT 0,
                          sumptuousness NUMERIC(3,2) DEFAULT 0,
                          culture ENUM('Canaanite','Hurrian','Luwian','Tejenu','Keftiu','Amurru','Shasu') NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.playercharacter (
                          id INT NOT NULL AUTO_INCREMENT,
                          userid INT NULL,
                          x INT NULL,
                          y INT NULL,
                          named VARCHAR(45) NULL,
                          swords INT DEFAULT 0,
                          staves INT DEFAULT 0,
                          cups INT DEFAULT 0,
                          discs INT DEFAULT 0,
                          culture ENUM('Egyptian','Canaanite','Hurrian','Luwian','Tejenu','Keftiu','Amurru','Shasu','Sangaru','Hittite') NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.agent (
                          id INT NOT NULL AUTO_INCREMENT,
                          named VARCHAR(45) NULL,
                          x INT NULL,
                          y INT NULL,
                          swords INT DEFAULT 0,
                          staves INT DEFAULT 0,
                          cups INT DEFAULT 0,
                          discs INT DEFAULT 0,
                          ptype ENUM('friendly', 'schemer', 'ruthless', 'cautious', 'bully', 'priest', 'workaholic') NULL,
                          culture ENUM('Egyptian','Canaanite','Hurrian','Luwian','Tejenu','Keftiu','Amurru','Shasu','Sangaru','Hittite') NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.clan (
                          id INT NOT NULL AUTO_INCREMENT,
                          tribe INT NULL,
                          ptype INT NULL,
                          x INT NULL,
                          y INT NULL,
                          population INT NULL,
                          fighters INT NULL,
                          food INT NULL,
                          coin INT NULL,
                          depot INT NULL,
                          activity ENUM('wandering', 'exploring', 'working', 'trading', 'holiday', 'fighting'),
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.building (
                          id INT NOT NULL AUTO_INCREMENT,
                          mapzone INT NULL,
                          named VARCHAR(45) NULL,
                          owner INT NULL,
                          region INT NULL,
                          topuser INT NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.army (
                          id INT NOT NULL AUTO_INCREMENT,
                          mapzone INT NULL,
                          ptype INT NULL,
                          owner INT NULL,
                          fighters INT NULL,
                          stance ENUM ('attack', 'police', 'avoid'),
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.depot (
                          id INT NOT NULL AUTO_INCREMENT,
                          wheat INT DEFAULT 0,
                          olives INT DEFAULT 0,
                          cattle INT DEFAULT 0,
                          copper INT DEFAULT 0,
                          fish INT DEFAULT 0,
                          incense INT DEFAULT 0,
                          wood INT DEFAULT 0,
                          linen INT DEFAULT 0,
                          gold INT DEFAULT 0,
                          dyes INT DEFAULT 0,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.diplomatic_relation (
                          id INT NOT NULL AUTO_INCREMENT,
                          ownerid INT NULL,
                          ownertype ENUM('player', 'agent', 'tribe', 'nation'),
                          targetid INT NULL,
                          targettype ENUM('player', 'agent', 'tribe', 'nation'),
                          modifier INT NULL,
                          reasons VARCHAR(140) NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.diplomatic_status (
                          id INT NOT NULL AUTO_INCREMENT,
                          ownerid INT NULL,
                          ownertype ENUM('nation', 'tribe'),
                          targetid INT NULL,
                          targettype ENUM('nation', 'tribe'),
                          status ENUM('Peace','War','Alliance','Truce') NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.battle (
                          id INT NOT NULL AUTO_INCREMENT,
                          mapzone INT NULL,
                          clans NOT NULL,
                          PRIMARY KEY (id));)";
    }
}
