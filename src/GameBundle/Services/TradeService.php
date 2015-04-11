<?php

namespace GameBundle\Services;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\Mapzone;
use GameBundle\Game\Model\TradegoodToken;
use GameBundle\Game\Model\TradegoodPlatonic;

/**
 * Class TradeService
 * @package GameBundle\Services
 */
class TradeService
{
    /**
     * Components
     * @var DBCommon $db */
    protected $db;

    /** @param DBCommon */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return TradegoodPlatonic
     */
    public function getARandomTradegoodPlatonic()
    {
        $query = 'SELECT id from tradegoodplatonic ORDER BY Rand() LIMIT 1;';
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObject();
        $tradegood = new TradegoodPlatonic($loadObj->id);
        $tradegood->setDb($this->db);
        $tradegood->load();
        return $tradegood;
    }

    /**
     * @param Mapzone $mz
     * @param TradegoodPlatonic $tg
     */
    public function insertANewTradegoodToken(Mapzone $mz, TradegoodPlatonic $tg)
    {
        $query = 'INSERT INTO tradegoodtoken(mapzone, tg, named, tradevalue, foodvalue) VALUES(' .$mz->getId(). ', ' .$tg->getId(). ', "' .$tg->getNamed(). '", ' .$tg->getTradevalue(). ', ' .$tg->getFoodvalue(). ');';
        $this->db->setQuery($query);
        $this->db->query();
    }

    /**
     * Returns the id of the most valuable game.tradegoodplatonic in the mapzone
     *
     * @param Mapzone $mz
     * @return int
     */
    public function exploreForTrade(Mapzone $mz)
    {
        $query = 'SELECT tg FROM tradegoodtoken WHERE mapzone=' .$mz->getId(). ' ORDER BY tradevalue ASC LIMIT 1;';
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObject();
        if (isset($loadObj)) {
            return $loadObj->tg;
        } else {
            return null;
        }
    }

    /**
     * @param Mapzone $mz
     * @return Array|TradegoodToken
     */
    public function searchZoneForTradegoodTokens(Mapzone $mz) {
        $tokens = [];
        $query = 'SELECT id FROM tradegoodtoken WHERE mapzone=' .$mz->getId(). ';';
        $this->db->setQuery($query);
        $this->db->query();
        $loadObj = $this->db->loadObjectList();
        foreach ($loadObj as $obj) {
            $tgt = new TradegoodToken($obj->id);
            $tgt->setDb($this->db);
            $tgt->load();
            $tokens[] = $tgt;
        }
        return $tokens;
    }
}