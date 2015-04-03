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
use GameBundle\Game\Model\Mapzone;
use GameBundle\Game\Model\News;

class RandomEvents
{

    /** @var $db DBCommon */
    protected $db;

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function rollDice() {
        // Pick and invoke random event
        $dice = new Dice();

        if ($dice->Odds(5))
        {
            return $this->NewTradeTokenEvent();
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
    private function NewTradeTokenEvent()
    {
        /* This event causes a random tradegood_token to appear in a mapzone where
           there are currently fewer than three tradegoods. Note that this event will
           fail sometimes because it drew a mapzone with three tokens already present. */
        $mapService = new MapService();
        $mapService->setDb($this->db);
        $mz = $mapService->getRandomPassableMapZone();
        $tradegood = $mapService->searchZoneForTradegoodTokens($mz);

        if (count($tradegood) <= 2)
        {
            $tg = $mapService->getARandomTradegoodPlatonic();
            $mapService->insertANewTradegoodToken($mz, $tg);
            $this->createSomeNews(ucwords($tg->getNamed()). ' will now be produced in ' .$mz->getX(). ', ' .$mz->getY(). '', $mz->getX(), $mz->getY());
            return ucwords($tg->getNamed()). ' will now be produced in ' .$mz->getX(). ', ' .$mz->getY();
        }
    }

    private function createSomeNews($msg, $x, $y)
    {
        $news = new News(null);
        $news->setText($msg);
        $news->setX($x);
        $news->setY($y);
        $news->update();
    }
}