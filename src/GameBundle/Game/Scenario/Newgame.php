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

namespace GameBundle\Game\Scenario;
use Symfony\Component\HttpKernel;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\Tribe;
use GameBundle\Services\MapService;
use GameBundle\Services\TribeService;
use GameBundle\Services\TradeService;
use GameBundle\Game\Simulation\RandomEvents\RandomEvents;

/**
 * Class Newgame
 * @package GameBundle\Services
 */
class Newgame
{
    /**
     * Components
     * @var DBCommon
     * @var TribeService
     * @var TradeService
     * @var RandomEvents
     */
    protected $db;
    protected $map;
    protected $tribes;
    protected $trade;
    protected $events;

    /**
     * Fields
     * @var int
     */
    protected $path;

    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @param MapService $map
     */
    public function setMap($map)
    {
        $this->map = $map;
    }

    /**
     * @param TribeService $tribeService
     */
    public function setTribes($tribeService)
    {
        $this->tribes = $tribeService;
    }

    /**
     * @param TradeService $tradeService
     */
    public function setTrade($tradeService)
    {
        $this->trade = $tradeService;
    }

    /**
     * @param RandomEvents
     */
    public function setEvents($randomEvents) {
        $this->events = $randomEvents;
    }

    /**
     *
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
     */
    public function createGame()
    {
            $this->trashGameworldTables();
            $this->setupGameworldTables();

            $this->createMap();

            $this->initializeCities();
            $this->initializeTradeGoods();

            for ($i = 0; $i < 325; $i++)
            {
                $this->events->NewTradeTokenEvent();
            }

            // $this->createTradeGoodTokens();
            $this->createSomeTribes();
            $this->createSomeClans();
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
    protected function createMap()
    {
        // Create 400 map zones, randomize geotype and link each with an abstract x,y
        for ($x = 0; $x < 60; $x++) {
            for ($y = 0; $y < 60; $y++) {

                $query = "INSERT INTO mapzone(x, y, geotype) VALUE (" . $x . ", " . $y . ", " . rand(1, 7) . ");";
                $this->db->setQuery($query);
                $this->db->query();
            }
        }
        // Create the Mediterranean sea
        $query = "UPDATE mapzone SET geotype='deepsea' WHERE x<=42 AND y>13 AND y<42;";
        $this->db->setQuery($query);
        $this->db->query();

        // Create the bay north of Lebanon
        $query = "UPDATE mapzone SET geotype='shallowsea' WHERE x>= 41 AND x<=45 AND y>12 AND y<22;";
        $this->db->setQuery($query);
        $this->db->query();

        // Create the Aegean/Bay of Tripoli
        $query = "UPDATE mapzone SET geotype='deepsea' WHERE x<12;";
        $this->db->setQuery($query);
        $this->db->query();

        // Create Africa and the Arabian desert
        $query = "UPDATE mapzone SET geotype='desert' WHERE y>40 AND geotype='shallowsea' OR y>40 AND geotype='forest';";
        $this->db->setQuery($query);
        $this->db->query();

        // Create the Nile river
        $query = "UPDATE mapzone SET geotype='swamp' WHERE x>28 AND x<42 AND y>40 AND geotype='hills' OR x>28 AND x<40 AND y>40 AND geotype='mountains';";
        $this->db->setQuery($query);
        $this->db->query();
        $query = "UPDATE mapzone SET geotype='shallowsea' WHERE x=28 AND y>40;";
        $this->db->setQuery($query);
        $this->db->query();
        $query = "UPDATE mapzone SET geotype='shallowsea' WHERE x=25 AND y>40;";
        $this->db->setQuery($query);
        $this->db->query();
        $query = "UPDATE mapzone SET geotype='shallowsea' WHERE x=21 AND y>40;";
        $this->db->setQuery($query);
        $this->db->query();

        // Create Syria and Turkey
        $query = "UPDATE mapzone SET geotype='hills' WHERE y<20 AND geotype='desert'"
            . " OR x<20 AND geotype='swamp';";
        $this->db->setQuery($query);
        $this->db->query();

        // Create the fictional island of Phaito
        $query = "UPDATE mapzone SET geotype='hills' WHERE x>3 AND x<8 AND y>0 AND y<4;";
        $this->db->setQuery($query);
        $this->db->query();
    }

    /**
     *  Object literal must be abstracted to a json
     *
     */
    protected function initializeCities()
    {
        $cities = array(
            'Ugarit' => array(
                'Description' => '',
                'x' => 41,
                'y' => 13,
                'Region' => 'Urartu',
                'God' => 'Yam'),
            'Qadesh' => array(
                'Description' => '',
                'x' => 53,
                'y' => 14,
                'Region' => 'Amurru',
                'God' => 'Qadeshtu'),
            'Hamath' => array(
                'Description' => '',
                'x' => 57,
                'y' => 3,
                'Region' => 'Nuhasse',
                'God' => 'Tesub'),
            'Arvad' => array(
                'Description' => '',
                'x' => 45,
                'y' => 18,
                'Region' => 'Jazirat',
                'God' => 'Mirizir'),
            'Gubla' => array(
                'Description' => '',
                'x' => 44,
                'y' => 23,
                'Region' => 'Gubla',
                'God' => 'Amun'),
            'Tyre' => array(
                'Description' => '',
                'x' => 42,
                'y' => 29,
                'Region' => 'Tyre',
                'God' => 'Melkart'),
            'Shechem' => array(
                'Description' => '',
                'x' => 55,
                'y' => 35,
                'Region' => 'Nahal Iron',
                'God' => 'El'),
            'Megiddo' => array(
                'Description' => '',
                'x' => 54,
                'y' => 30,
                'Region' => 'Megiddo',
                'God' => 'Shapash'),
            'Asqalun' => array(
                'Description' => '',
                'x' => 43,
                'y' => 42,
                'Region' => 'Asqanu',
                'God' => 'El'),
            'Gezer' => array(
                'Description' => '',
                'x' => 50,
                'y' => 32,
                'Region' => 'Nahal Iron',
                'God' => 'Amun'),
            'Phaito' => array(
                'Description' => '',
                'x' => 5,
                'y' => 1,
                'Region' => 'Keftiu',
                'God' => 'Astarte'),
            'Tanit' => array(
                'Description' => '',
                'x' => 37,
                'y' => 44,
                'Region' => 'Goshen',
                'God' => 'Ptah'),
            'Bubastis' => array(
                'Description' => '',
                'x' => 30,
                'y' => 51,
                'Region' => 'Egypt',
                'God' => 'Bast'),
            );

        foreach ($cities as $k => $v)
        {
            $query = "UPDATE mapzone SET geotype='plains' WHERE x=" .$v['x']. " AND y=" .$v['y']. ";";
            $this->db->setQuery($query);
            $this->db->query();

            $city = $this->createCity($k, $v['Description'], $v['x'], $v['y']);
            $this->createRegion($v['Region'], $v['God'], $city);
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
        foreach ($tradegoods[0] as $k => $v)
        {
            $tradegood = $tradegoods[0][$k];   // Pull the specific object

            // Get the data from the random tradegood
            $name = $tradegood['Name'];
            $description = $tradegood['Description'];
            $fv = $tradegood['Food Value'];
            $tv = $tradegood['Trade Value'];
            $tgtype = $tradegood['Type'];

            // Insert into game.tradegood
            $this->createTradeGood($name, $description, $tv, $fv, $tgtype);
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
                "Margod", "Mawat", "Melwart", "Nikkal", "Shalim", "Shachar", "Qadeshtu", "Yarikh", "Yaw"),
            'Hurrian' => array("Matarum", "Yariha-amu", "Ihid", "Iniru", "Sin-mslim", "Abbana-el",
                "Yai-tobal", "Yimsi-el", "Mut-rame", "Habdu-ami", "Kabi-epuh", "Zakija-Ham", "Zunan"),
            'Luwian' => array("Madunani", "Kuruntya", "Runtya", "Hantawti", "Tarkasn", "Zupari",
                "Tupana", "Tuwrsanza", "Tibe", "Esi-tmata", "Manaha", "Umanaddu", "Musisipa"),
            'Tejenu' => array("Andronak", "Etewokwt", "Klonak", "Nikostur",
                "Khalkeos", "Xarihmo", "Kaliode", "Kupirijo", "Makhawon", 'Glaukos'),
            'Keftiu' => array('Tinay', 'Noso', 'Cukra', 'Paito', 'Ouran', 'Tros',
                                'Ida', 'Malia', 'Zakra', 'Melo', 'Kea', 'Zauro', 'Filarto'),
            'Amurru' => array("Yarikhu", "Rabbu", "Uprapu", "Yakhruru", "Mikhalzyu", "Almutu",
                "Numkha", "Aqba-el", "Yamutbal", "Ya'ilanu", "Sim'alits", "Amnanu", "Zamri-Lim"),
            'Shasu' => array("Jetheth", "Oholibmah", "Mibzar", "Iram-amon", "Kenaz", "Pinon",
                "Timnah", "Magdiel", "Elah", "Zepho", "Kenaz", "Mizzah", "Nathath")
        );

        for ($i = 1; $i < 95; $i++)
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
            $tid = $tribe->getId();
            $this->createClan($tid);
            if (rand(1,10)==10) { $this->CreateClan($tid); }
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
     * @param int $x
     * @param int $y
     * @return int
     */
    private function createCity($name, $desc, $x, $y)
    {
        $query = "INSERT INTO city(named, description, x, y) VALUES('" . $name . "', '" . $desc . "', " . $x . ", " . $y . ");";
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
     * @param string $named
     * @param string $description
     * @param float $tv
     * @param float $fv
     * @param int $tgtype
     */
    private function createTradeGood($named, $description, $tv, $fv, $tgtype)
    {
        $query = "INSERT INTO tradegoodplatonic(named, description, tradevalue, foodvalue, tgtype) VALUES('" . $named . "','" . $description . "'," . $tv . "," . $fv . "," . $tgtype . ");";
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
        $query = "SELECT named FROM tribe WHERE id=" . $tribeId . ";";
        $this->db->setQuery($query);
        $this->db->query();
        $tribeName = $this->db->loadResult();
        $MapService = new MapService();
        $MapService->setDb($this->db);
        $mz = $MapService->getRandomPassableMapZone();
        $x = $mz->getX();
        $y = $mz->getY();
        $query = "INSERT INTO clan(named, tribe, x, y, depot, population, fighters, morale, food, coin, activity) VALUES('"
                    .$tribeName. "', " .$tribeId. ", " .$x. ', ' .$y. ', ' .$depotId. ", 100, 60, 100, 35, 0, 'wandering');";
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

        $query = "DROP TABLE city;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE region;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE tradegoodplatonic;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE tradegoodtoken;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE agent;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE estate;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE persona;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE useraccount;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE clan;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE tribe;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE depot;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE news;";
        $this->db->setQuery($query);
        $this->db->query();

    }

    /**
     *
     */
    private function setupGameworldTables()
    {
        // Here is the user info gathered during the account creation process.
        // A characterid value of 0 means the player has no character and will
        // produce a prompt to go create one on login.
        $query = "CREATE TABLE game.useraccount (
                          id INT NOT NULL AUTO_INCREMENT,
                          username VARCHAR(18),
                          password VARCHAR(28),
                          email VARCHAR(45),
                          characterid INT DEFAULT 0,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        // Mapzone, City and Region are all related structures. Regions all refer to a capital city and all of these
        // things are regularly checked by map-logic calls from all the other objects. The interface IMappable used
        // by the Rules module refers exclusively to the possession of X and Y columns.
        $query = "CREATE TABLE game.mapzone (
                          id INT NOT NULL AUTO_INCREMENT,
                          x INT(2) NOT NULL,
                          y INT(2) NOT NULL,
                          geotype ENUM('plains','hills','mountains','desert','swamp','forest','shallowsea','deepsea') NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.city (
                          id INT NOT NULL AUTO_INCREMENT,
                          named CHAR(45) NULL,
                          description VARCHAR(160) NULL,
                          tradeincome DECIMAL(3,2) DEFAULT 0,
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
                          god VARCHAR(45) NULL,
                          city INT NULL,
                          ruledby INT NULL,
                          radius INT NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        // Platonic tradegoods are the bearers of data pertinent to whole categories of tradegood. These are the 'types'
        // from which the tokens are struck. Tokens, of course, are individual instances of exploitable tradegoods
        // on the map. Tradegoodtoken.TG points to the ID of the platonic tradegood from which this instance inherits.
        // TradeValue and FoodValue can change in response to events for individual tokens.
        $query = "CREATE TABLE game.tradegoodplatonic (
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

        $query = "CREATE TABLE game.tradegoodtoken (
                          id INT NOT NULL AUTO_INCREMENT,
                          mapzone INT NULL,
                          tg INT NULL,
                          named VARCHAR(45) NULL,
                          tradevalue NUMERIC(2,1) NULL,
                          foodvalue NUMERIC(2,1) NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        // Tribes and Clans define parts of an interlocking system similar to the platonic/token relationship with
        // tradegoods. A 'clan' is a specific group of 100-200 people traveling on the map; each clan points to a 'tribe'
        // which accounts for its personality and allegiances. New 'clans' are usually struck off of existing clans,
        // preserving the tribal identity.
        //
        // At a gameplay level, the tribes and clans are the restive natives over whom you have little control.
        // They do the gruntwork of the urban merchants and make up a rough third of the prince's support.
        $query = "CREATE TABLE game.tribe (
                          id INT NOT NULL AUTO_INCREMENT,
                          named VARCHAR(45) NULL,
                          gregariousness NUMERIC(3,2) DEFAULT 0,
                          belligerence NUMERIC(3,2) DEFAULT 0,
                          tenacity NUMERIC(3,2) DEFAULT 0,
                          insularity NUMERIC(3,2) DEFAULT 0,
                          spirituality NUMERIC(3,2) DEFAULT 0,
                          sumptuousness NUMERIC(3,2) DEFAULT 0,
                          culture ENUM('Kananu','Hurru','Luwwiyu','Tejenu','Keftiu','Amurru','Shasu') NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.clan (
                          id INT NOT NULL AUTO_INCREMENT,
                          named VARCHAR(45) NULL,
                          tribe INT NULL,
                          ptype INT NULL,
                          x INT NULL,
                          y INT NULL,
                          population INT NULL,
                          fighters INT NULL,
                          morale INT NULL,
                          food INT NULL,
                          coin INT NULL,
                          depot INT NULL,
                          activity ENUM('wandering', 'exploring', 'working', 'trading', 'holiday', 'fighting'),
                          producing VARCHAR(45) NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        // Characters are either players (in which case UserID will point to a user's primary key) or agents (in which
        // case PType will not be null). Characters are necessarily tied to a city by an estate, upon which they can
        // build a variety of addons (holdings points to the ID of an estate table). Characters also have a persona
        // containing all their "character-sheet" details.
        //
        // Most of the gameplay surrounds the doings of characters, who are understood to be the merchant classes living
        // in the urban centers. Players, additionally, also begin with allegiance to a kingdom and are assumed to be
        // foreign envoys by origin.
        $query = "CREATE TABLE game.agent (
                          id INT NOT NULL AUTO_INCREMENT,
                          isplayer BOOL DEFAULT FALSE,
                          ptype ENUM('friendly', 'schemer', 'ambitious', 'cautious', 'bully', 'priest', 'weirdo', 'workaholic') NULL,
                          userid INT NULL,
                          activity VARCHAR(45) NULL,
                          x INT NULL,
                          y INT NULL,
                          named VARCHAR(45) NULL,
                          culture ENUM('Kananu','Hurru','Luwwiyu','Tejenu','Keftiu','Amurru','Shasu') NULL,
                          tradeincome DECIMAL(3,2) DEFAULT 0,
                          coin INT DEFAULT 0,
                          city INT NULL,
                          holdings INT NULL,
                          persona INT NULL,
                          allegiance ENUM('Egypt', 'Babylon', 'Hatti', 'none') NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.estate (
                          id INT NOT NULL AUTO_INCREMENT,
                          depot INT DEFAULT 0,
                          field INT DEFAULT 0,
                          quay INT DEFAULT 0,
                          caravansary INT DEFAULT 0,
                          garden INT DEFAULT 0,
                          beerhouse INT DEFAULT 0,
                          barque INT DEFAULT 0,
                          shrine INT DEFAULT 0,
                          palace INT DEFAULT 0,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.persona(
                          id INT NOT NULL AUTO_INCREMENT,
                          fame INT DEFAULT 0,
                          honor INT DEFAULT 0,
                          controversy INT DEFAULT 0,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        // Depots contain the slots for a single store of tradegoods. A wide variety of game entities need
        // to have depots to refer to. (Note that for characters a depot is optional and will be stored as a foreign
        // key inside of a building list.)
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

        // The news table receives text output from a variety of Simulation sources; one imagines that it will
        // need to be cleared out occasionally. News flagged 'important' will take longer to clear and will show
        // up on the "grand timeline" view.
        $query = "CREATE TABLE game.news (
                          id INT NOT NULL AUTO_INCREMENT,
                          text VARCHAR(144) NOT NULL,
                          x INT NULL,
                          y INT NULL,
                          dated TIMESTAMP,
                          important BOOLEAN DEFAULT FALSE,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();
    }
}
