<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 4/1/2015
 * Time: 11:45 PM
 */

namespace GameBundle\Game\Simulation\AI\Clans;

use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\Clan;
use GameBundle\Game\Model\City;
use GameBundle\Game\Model\TradegoodPlatonic;
use GameBundle\Game\Model\Depot;
use GameBundle\Services\ClanService;
use GameBundle\Services\MapService;
use GameBundle\Services\NewsService;
use GameBundle\Services\TribeService;
use GameBundle\Services\TradeService;
use GameBundle\Game\Rules\Rules;

/**
 * Class Behavior
 * @package GameBundle\Game\Simulation\AI\Clans
 */
class Behavior
{

    /** @var DBCommon $db **/
    protected $db;
    /** @var MapService */
    protected $map;
    /** @var Rules $rules **/
    protected $rules;
    /** @var NewsService $news**/
    protected $news;
    /** @var TribeService $tribes**/
    protected $tribes;
    /** @var TradeService $trade**/
    protected $trade;
    /** @var ClanService $clans **/
    protected $clans;

    /**
     * @param Rules
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param NewsService
     */
    public function setNews($news)
    {
        $this->news = $news;
    }

    /** @param TribeService */
    public function setTribes($tribes)
    {
        $this->tribes = $tribes;
    }

    /** @param ClanService */
    public function setClans($clans)
    {
        $this->clans = $clans;
    }

    /**
     * @param MapService
     */
    public function setMap($map)
    {
        $this->map = $map;
    }

    /**
     * @param TradeService
     */
    public function setTrade($trade)
    {
        $this->trade = $trade;
    }

    /**
     * @param DBCommon
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @param int $clanid
     * @return string
     */
    public function TakeAction($clanid)
    {
        $clan = new Clan($clanid);
        $clan->setDb($this->db);
        $clan->load();
        $depot = new Depot($clan->getDepot());
        $depot->setDb($this->db);
        $depot->load();

        $action = [];

        // Consume food or starve
        $this->ConsumeFood($clan);

        // Clans periodically process their food-type tokens for personal consumption
        if (rand(1, 10) == 10) {
            $yield = $clan->getFood() + $depot->fillLarder();
            $clan->setFood($yield);
            $clan->update();
        }

        // Clans periodically reconsider and revise their current activity
        // according exigencies, leader ptype, etc.
        if (rand(1, 20) == 20) {
            $activity = $this->Consider($clan);
            return 'Clan' . $clan->getId() . ' reconsidered and ' . $activity;
        }

        // ... finally, we have the behavior state-machine itself where the current
        // clan->activity is processed once per ActionRound

        switch ($clan->getActivity()) {
            case 'wandering':

                $action['Action'] = 'travel';
                $action['Issuer'] = $clan;
                $mz = $this->map->GetARandomMove($clan);
                $action['Args'] = $mz->getX() . ',' . $mz->getY();
                $result = $this->rules->submit($action);

                if ($result['Type'] == 'Success') {
                    if (rand(1, 3) == 3) {
                        $result['Description'] .= ' and began exploring';
                        $clan->setActivity('exploring');
                        $clan->update();
                    }
                }

                return $result['Description'];

            case 'exploring':

                // Check my local zone and see if there are any trade
                // tokens to produce

                $mz = $this->map->getMapzoneFromAbstract($clan->getX(), $clan->getY());
                $producing = $this->trade->exploreForTrade($mz);

                if (is_null($producing)) {
                    $clan->setActivity('wandering');
                } else {
                    $clan->setProducing($producing);
                    $clan->setActivity('working');
                }
                $clan->update();

                return 'Clan' . $clan->getId() . ' explored ' . $clan->getX() . ', ' . $clan->getY();

            case 'working':

                // If I am producing anything, produce one and deposit it
                // into my depot. If I am full up, change activity to
                // 'trading'

                $tgp = new TradegoodPlatonic($clan->getProducing());
                $tgp->setDb($this->db);
                $tgp->load();

                if (rand(1, 5) < 5) {
                    $depot->Produce(strtolower($tgp->getNamed()));
                    $result = 'Clan' . $clan->getId() . ' produced ' . $tgp->getNamed() . ' in ' . $clan->getX() . ', ' . $clan->getY();
                } else {
                    $result = 'Clan' . $clan->getId() . ' labored without producing sufficient ' . $tgp->getNamed();
                }

                if ($depot->check(strtolower($tgp->getNamed())) >= 10) {
                    $clan->setProducing(null);
                    $clan->setActivity('trading');
                    $clan->update();
                    $result = 'Clan' . $clan->getId() . ' completed work and is seeking to trade';
                }

                return $result;

            case 'trading':

                // If I'm in a city now, do buy and sell behavior. If I'm in teleport
                // range of a city, teleport there. If I'm truly in the wild, revert
                // behavior to wandering.
                $city = $this->map->findNearestCity($clan->getX(), $clan->getY());

                if (isset($city)) {
                    if (($city->getX() == $clan->getX()) && ($city->getY() == $clan->getY())) {
                        $result = '';
                        $result .= $this->clanSellBehavior($clan, $depot, $city);
                        if ($clan->getCoin() > 65) {
                            $result .= $this->clanBuyBehavior($clan, $city);
                        }
                        return $result;
                    } else {
                        $this->map->teleportCity($clan, $city);
                        return 'Clan' . $clan->getId() . ' traveled to ' . $city->getNamed();
                    }
                } else {
                    $clan->setActivity('wandering');
                    $clan->update();
                    return 'Clan ' . $clan->getId() . ' wanted to trade but was not near a market.';
                }

            case 'holiday':

                // If we can afford to, let's party
                if ($clan->getFood() >= 150) {
                    $clan->setFood($clan->getId(), ($clan->getFood() - 100));

                    if (($clan->getPopulation() >= 200) && (rand(0, $clan->getPopulation()) > 500)) {
                        $clan->setPopulation($clan->getPopulation() - 150);
                        $this->tribes->createClan($clan->getTribeId());
                        $this->news->createSomeNews('A family of clan' . $clan->Id() . ' went to seek new pastures', $clan->getY(), $clan->getY());
                    } else {
                        $clan->setPopulation($clan->getPopulation(), ($clan->getPopulation() + (25 + rand(1, 7))));
                    }
                    $clan->update();

                    return 'Clan' . $clan->getId() . ' celebrated a holy day';
                } else {
                    return 'Clan' . $clan->getId() . ' reconsidered and ' . $this->Consider($clan);
                }

            default:
                return 'Clan' . $clan->getId() . ' did inscrutable things on a hill';
        }
    }

    /*
     *
     *
     *
     *                                      PROTECTED FUNCTIONS
     *
     *
     *
     */

    /**
     * @param Clan $clan
     * @return string
     */
    protected function Consider(Clan $clan)
    {
        // First, let's cover well-fed exuberance and famine-driven panic
        if ($clan->getFood() >= 150) {
            $clan->setActivity('holiday');
            $clan->update();
            return 'began celebrating';
        }

        if ($clan->getFood() <= 100) {
            $clan->setActivity('wandering');
            $clan->update();
            return 'began wandering';
        }

        if ($clan->getFood() <= 25) {
            $clan->setActivity('exploring');
            $clan->update();
            return 'began exploring';
        }

        return 'continued as it was';
    }


    /*
     *
     *
     *                                      PRIVATE FUNCTIONS
     *
     *
     *
     */


    /**
     * @param Clan $clan
     * @param Depot $depot
     * @param City $city
     * @return string
     */
    public function clanSellBehavior(Clan $clan, Depot $depot, City $city)
    {
        $output = '';
        $possessions = $depot->Assess();
        foreach ($possessions as $possession) {
            if ($possession->getTgtype() != 'food') {
                $amt = $depot->GetValueByString($possession->getNamed());
                $request = $this->rules->createRequest($clan, 'sell goods', $city->getId() . ',' . $possession->getId() . ',' . $amt);
                $result = $this->rules->submit($request);
                $output .= 'Clan' . $clan->getId() . ' ' . $result['Description'];
            }
        }
        return $output;
    }

    /**
     * @param Clan $clan
     * @param City $city
     * @return mixed
     */
    public function clanBuyBehavior(Clan $clan, City $city)
    {
        $request = $this->rules->createRequest($clan, 'buy goods', $city->getId() . ',1,25');
        $result = $this->rules->submit($request);
        return $result['Description'];
    }

    /**
     * @param Clan $clan
     */
    public function consumeFood(Clan $clan)
    {
        $consumption = intval($clan->getPopulation() * 0.1);
        $newamt = $clan->getFood() - $consumption;
        if ($newamt <= 0) {
            if (rand(1, 3) == 3) {
                $clan->setPopulation($clan->getPopulation() - 1);
                $clan->setFood(0);
                $this->news->createSomeNews('Clan' . $clan->getId() . ' are starving', $clan->getX(), $clan->getY());
            } else {
                $clan->setFood(0);
            }
        } else {
            $clan->setFood($newamt);
        }
        $clan->update();
    }
}