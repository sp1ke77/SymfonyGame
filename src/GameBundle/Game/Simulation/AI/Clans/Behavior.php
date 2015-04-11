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

    /** @var DBCommon $db
     * @var Rules $rules
     * @var NewsService $news
     * @var MapService $map
     * @var TribeService $tribes
     * @var TradeService $trade
     * @var ClanService $clans
     */
    protected $db;
    protected $rules;
    protected $news;
    protected $map;
    protected $tribes;
    protected $trade;
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
     * @param $clanId
     * @return string
     */
    public function TakeAction($clanId)
    {
        $clan = new Clan($clanId);
        $clan->setDb($this->db);
        $clan->load();
        $depot = new Depot($clan->getDepot());
        $depot->setDb($this->db);
        $depot->load();

        $action = [];

        // Consume food or starve
        if (rand(1, 10) == 20) {
            $this->ConsumeFood($clan);
        }

        // Some stuff that happens occasionally regardless of the current behavior ...
        // Clans periodically process their food-type trade tokens for personal consumption
        if (rand(1, 10) == 10) {
            $yield = $clan->getFood() + $depot->fillLarder();
            $this->clans->setFood($clan->getId(), $yield);
        }

        // Clans periodically reconsider and revise their current activity
        // according current exigencies, leader ptype, etc.
        if (rand(1, 10) == 10) {
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
                        $this->clans->changeActivity($clan, 'exploring');
                    }
                }

                return $result['Description'];

            case 'exploring':

                // Check my local zone and see if there are any trade
                // tokens to produce

                $mz = $this->map->getMapzoneFromAbstract($clan->getX(), $clan->getY());
                $producing = $this->trade->exploreForTrade($mz);

                if (is_null($producing)) {
                    $this->clans->changeActivity($clan, 'wandering');
                } else {
                    $this->clans->changeProducing($clan, $producing);
                    $this->clans->changeActivity($clan, 'working');
                }

                return 'Clan' . $clan->getId() . ' explored ' . $clan->getX() . ', ' . $clan->getY();

            case 'working':

                // If I am producing anything, produce one and deposit it
                // into my depot. If I am full up, change activity to
                // 'trading'

                $tgp = new TradegoodPlatonic($clan->getProducing());
                $tgp->setDb($this->db);
                $tgp->load();

                if (rand(1, 3) == 3) {
                    $depot->Produce(strtolower($tgp->getNamed()));
                    $result = 'Clan' . $clan->getId() . ' produced ' . $tgp->getNamed() . ' in ' . $clan->getX() . ', ' . $clan->getY();
                } else {
                    $result = 'Clan' . $clan->getId() . ' labored without producing sufficient ' . $tgp->getNamed();
                }

                if ($depot->check(strtolower($tgp->getNamed())) >= 10) {
                    $this->clans->changeProducing($clan, null);
                    $this->clans->changeActivity($clan, 'trading');
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
                        $result .= $this->clanSellBehavior($clan, $depot);
                        if ($clan->getCoin() > 65) {
                            $result .= $this->clanBuyBehavior($clan);
                        }
                        return $result;
                    } else {
                        $this->map->teleportCity($clan, $city);
                        return 'Clan' . $clan->getId() . ' traveled to ' . $city->getNamed();
                    }
                } else {
                    $this->clans->changeActivity($clan, 'wandering');
                    return 'Clan ' . $clan->getId() . ' wanted to trade but was not near a market.';
                }

            case 'holiday':

                $depot = new Depot($clan->getDepot());
                $depot->setDb($this->db);
                $depot->load();

                // If we can afford to, let's party
                if ($clan->getFood() >= 150) {
                    $this->clans->setFood($clan->getId(), ($clan->getFood() - 100));

                    if (($clan->getPopulation() >= 200) && (rand(0, $clan->getPopulation()) > 500)) {
                        $this->clans->setPopulation($clan->getPopulation(), ($clan->getPopulation() - 150));
                        $this->tribes->createClan($clan->getTribeId());
                        $this->news->createSomeNews('A family of clan' . $clan->Id() . ' went to seek new pastures', $clan->getY(), $clan->getY());
                    } else {
                        $this->clans->setPopulation($clan->getPopulation(), ($clan->getPopulation() + (25 + rand(1, 7))));
                    }

                    return 'Clan' . $clan->getId() . ' celebrated a holy day';
                } else {
                    return 'Clan' . $clan->getId() . ' reconsidered and ' . $this->Consider($clan);
                }

                break;

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
            $this->clans->changeActivity($clan, 'holiday');
            return 'began celebrating';
        }

        if ($clan->getFood() <= 100) {
            $this->clans->changeActivity($clan, 'wandering');
            return 'began wandering';
        }

        if ($clan->getFood() <= 25) {
            $this->clans->changeActivity($clan, 'exploring');
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
     * @return string
     */
    public function clanSellBehavior(Clan $clan, Depot $depot)
    {
        $output = '';
        $possessions = $depot->Assess();
        foreach ($possessions as $possession) {
            if ($possession->getTgtype() != 'food') {
                $amt = $depot->GetValueByString($possession->getNamed());
                $request = $this->rules->createRequest($clan, 'sell goods', $possession->getId() . ',' . $amt);
                $result = $this->rules->submit($request);
                $output .= 'Clan' . $clan->getId() . ' ' . $result['Description'];
            }
        }
        return $output;
    }

    /**
     * @param Clan $clan
     * @return mixed
     */
    public function clanBuyBehavior(Clan $clan)
    {
        $request = $this->rules->createRequest($clan, 'buy goods', '1,25');
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
            if (rand(1, 5) == 5) {
                $this->clans->setPopulation($clan->getId(), ($clan->getPopulation() - 6));
                $this->news->createSomeNews('Clan' . $clan->getId() . ' are starving');
            } else {
                $this->clans->setPopulation($clan->getId(), ($clan->getPopulation() - 1));
            }
        }

        $this->clans->setFood($clan->getId(), $newamt);
    }
}