<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/19/2015
 * Time: 5:43 PM
 */

namespace GameBundle\Services;
use GameBundle\Game\DBCommon;
use \Exception as Exception;

class GameworldAdminService
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

    public function trashGameworldRecords()
    {
        // drop all records for current game
        $query = "DROP TABLE map;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE mapzone;";
        $this->db->setQuery($query);
        $result = $this->db->query();

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

        $query = "DROP TABLE diplo_relation;";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "DROP TABLE diplomatic_status;";
        $this->db->setQuery($query);
        $this->db->query();

    }

    public function setupGameworldRecords()
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

        $query = "CREATE TABLE game.tradegood (
                          id INT NOT NULL AUTO_INCREMENT,
                          named VARCHAR(45) NULL,
                          imgfull VARCHAR(45) NULL,
                          description VARCHAR(160) NULL,
                          tradevalue NUMERIC(1,1) NULL,
                          foodvalue NUMERIC(1,1) NULL,
                          tgtype ENUM('food','supplies','goods','gifts') NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.tribe (
                          id INT NOT NULL AUTO_INCREMENT,
                          named VARCHAR(45) NULL,
                          culture ENUM('Egyptian','Canaanite','Hurrian','Luwian','Tejenu','Keftiu','Amorite','Shasu','Chaldean','Hittite') NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.playercharacter (
                          id INT NOT NULL AUTO_INCREMENT,
                          userid INT NULL,
                          mapzone INT NULL,
                          named VARCHAR(45) NULL,
                          culture ENUM('Egyptian','Canaanite','Hurrian','Luwian','Tejenu','Keftiu','Amorite','Shasu','Chaldean','Hittite') NULL,
                          depot INT NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.clan (
                          id INT NOT NULL AUTO_INCREMENT,
                          mapzone INT NULL,
                          ptype INT NULL,
                          tribe INT NULL,
                          population INT NULL,
                          fighters INT NULL,
                          depot INT NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.building (
                          id INT NOT NULL AUTO_INCREMENT,
                          named VARCHAR(45) NULL,
                          mapzone INT NULL,
                          owner INT NULL,
                          region INT NULL,
                          topuser INT NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.unit (
                          id INT NOT NULL AUTO_INCREMENT,
                          mapzone INT NULL,
                          ptype INT NULL,
                          owner INT NULL,
                          fighters INT NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.depot (
                          id INT NOT NULL AUTO_INCREMENT,
                          wheat INT NULL,
                          olives INT NULL,
                          cattle INT NULL,
                          copper INT NULL,
                          fish INT NULL,
                          incense INT NULL,
                          wood INT NULL,
                          linen INT NULL,
                          gold INT NULL,
                          dyes INT NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.diplomatic_relation (
                          id INT NOT NULL AUTO_INCREMENT,
                          characterid INT NULL,
                          target INT NULL,
                          modifier INT NULL,
                          reasons VARCHAR(140) NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();

        $query = "CREATE TABLE game.diplomatic_status (
                          id INT NOT NULL AUTO_INCREMENT,
                          tribe INT NULL,
                          target INT NULL,
                          status ENUM('Peace','War','Alliance','Truce') NULL,
                          PRIMARY KEY (id));";
        $this->db->setQuery($query);
        $this->db->query();
    }

}