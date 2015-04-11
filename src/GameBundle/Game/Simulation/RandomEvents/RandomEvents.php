<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 4/1/2015
 * Time: 11:55 PM
 */

namespace GameBundle\Game\Simulation\RandomEvents;
use GameBundle\Game\DBCommon;
use GameBundle\Services\MapService;
use GameBundle\Game\Rules\Dice;
use GameBundle\Services\NewsService;
use GameBundle\Services\TradeService;

class RandomEvents
{

    /** @var DBCommon $db
     * @var TradeService $trade
     * @var MapService $map
     * @var NewsService $news
     */
    protected $db;
    protected $trade;
    protected $map;
    protected $news;

    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @param mixed $trade
     */
    public function setTrade($trade)
    {
        $this->trade = $trade;
    }

    /**
     * @param mixed $map
     */
    public function setMap($map)
    {
        $this->map = $map;
    }

    /**
     * @param mixed $news
     */
    public function setNews($news)
    {
        $this->news = $news;
    }

    public function rollDice() {
        // Pick and invoke random event

        if (dice::Odds(5))
        {
            return $this->NewTradeTokenEvent();
        } else {
            return 'Nothing happened';
        }
    }

    /*
     *
     *
     *
     *
     *
     *                  PRIVATE FUNCTIONS
     *
     *
     *
     *
     *
     */

    /**
     * @return string
     */
    public function NewTradeTokenEvent()
    {
        /* This event causes a random tradegood_token to appear in a mapzone where
           there are currently fewer than three tradegoods. Note that this event will
           fail sometimes because it drew a mapzone with three tokens already present. */
        $mz = $this->map->getRandomPassableMapZone();
        $tradegood = $this->trade->searchZoneForTradegoodTokens($mz);

        if (count($tradegood) <= 2)
        {
            $tg = $this->trade->getARandomTradegoodPlatonic();
            $this->trade->insertANewTradegoodToken($mz, $tg);
            $this->news->createSomeNews(ucwords($tg->getNamed()). ' will now be produced in ' .$mz->getX(). ', ' .$mz->getY(). '', $mz->getX(), $mz->getY());
            return ucwords($tg->getNamed()). ' will now be produced in ' .$mz->getX(). ', ' .$mz->getY();
        }
    }
}