<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/23/2015
 * Time: 1:23 PM
 */

namespace GameBundle\Game\Rules;

use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\TradegoodPlatonic;
use GameBundle\Game\Rules\Interfaces\IMappable;
use GameBundle\Game\Rules\Interfaces\IDepotHaver;
use GameBundle\Game\Rules\Interfaces\ICombatant;
use GameBundle\Game\Model\Clan;
use GameBundle\Game\Model\City;
use GameBundle\Game\Model\Depot;
use GameBundle\Services\MapService;

/**
 * Class Rules
 *
 * Obviously, this should be constructed with the db and if you don't lols wil ensue.
 *
 * @package GameBundle\Game\Rules
 */
class Rules
{
    /**
     * Properties
     * @var string $status
     */
    protected $status;

    /**
     * Components
     * @var DBCommon $db
     * @var MapService $map
     */
    protected $db;
    /**
     * @var MapService $map
     */
    protected $map;

    /**
     * @param DBCommon $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @param MapService $mapService
     */
    public function setMap($mapService)
    {
        $this->map = $mapService;
    }

    /**
     * Create a request packaged for submission
     *
     * @param $issuer object
     * @param $action string
     * @param $args string
     * @return array
     */
    public function createRequest($issuer, $action, $args = null)
    {
        $request = [];
        $request['Action'] = $action;
        $request['Issuer'] = $issuer;
        $request['Args'] = $args;
        return $request;
    }

    /**
     * @param array $request an array containing arguments for the submitted request
     * @return array the outcome of the request
     */
    public function submit($request)
    {
        // Validate the request object
        if (empty($request['Action']) | empty($request['Issuer'])) {
            return $this->getResult('Invalid request', 'Missing action or issuer');
        }

        // Get the parameters and optional args
        $action = strtolower($request['Action']);
        $issuer = $request['Issuer'];
        if (empty($request['Args'])) {
            $args = null;
        } else {
            $args = $request['Args'];
        }

        // Pick a strategy
        switch ($action) {
            case 'travel':
                // The interface for travel is IMappable; Clans, Armies and Characters have it.
                // Implementing IMappable means having fields $x and $y (that is, a location) and
                // methods for getting and updating them.

                if (!array_search("GameBundle\\Game\\Rules\\Interfaces\\IMappable", class_implements($issuer)))
                {
                    return $this->getResult('Invalid request', 'Issuer must implement IMappable');
                }

                if (!$args) {
                    return $this->getResult('Invalid request', 'Travel requires Args: string "x,y"');
                } else {
                    $xy = explode(',', $args);
                    if (count($xy) != 2)
                    {
                        return $this->getResult('Invalid request', 'Travel requires Args: string "x,y"');
                    }
                    return $this->travel($issuer, $xy[0], $xy[1]);
                }

            case 'buy goods':

                // The interface for trading is IDepotHaver; Clans and Characters have it
                // (Cities technically have depots too, but they do not initiate trades).

                // Check if the issuer has enough coin, if the issuer is located in a city
                // with sufficient supplies and, if it all checks out, execute the trade.

                if (!array_search("GameBundle\\Game\\Rules\\Interfaces\\IDepotHaver", class_implements($issuer)))
                {
                    return $this->getResult('Invalid request', 'Issuer must implement IDepotHaver');
                }

                if (!$args) {
                    return $this->getResult('Invalid request', 'Buy Goods requires Args: string"');
                } else {
                    $xyz = explode(',', $args);
                    if (count($xyz) != 3)
                    {
                        return $this->getResult('Invalid request', 'Buy Goods requires Args: string "{city id},{good},{amount}"');
                    }
                    return $this->buyGoods($issuer, $xyz[0], $xyz[1], $xyz[2]);
                }

            case 'sell goods':

                if (!array_search("GameBundle\\Game\\Rules\\Interfaces\\IDepotHaver", class_implements($issuer)))
                {
                    return $this->getResult('Invalid request', 'Issuer must implement IDepotHaver');
                }

                if (!$args) {
                    return $this->getResult('Invalid request', 'Sell Goods requires Args: string"');
                } else {
                    $xyz = explode(',', $args);
                    if (count($xyz) != 3)
                    {
                        return $this->getResult('Invalid request', 'Sell Goods requires Args: string "{city id},{good},{amount}"');
                    }
                    return $this->sellGoods($issuer, $xyz[0], $xyz[1], $xyz[2]);
                }

            case 'attack':

                // Check if the issuer is IMappable and ICombatable, and if the target
                // is valid.

                // battle() {} {
                //      Check if the target is still in the area. If a battle already exists here,
                //      subscribe this unit. If the intercept check succeeds, subscribe the target
                //      too. If a battle does not exist here and the intercept check succeeds,
                //      create a new battle here and subscribe both units. The interface for all
                //      this stuff is ICombatable; Clans and Armies implement it, Characters don't.
                // }
                //
                // Args are probably just $target_id

            default:

                return $this->getResult('Invalid request', 'Undefined action');
        }
    }

    /*
     *
     *
     *
     *                      STRATEGIES
     *
     *
     *
     */

    /**
     * @param IMappable $issuer
     * @param $x2
     * @param $y2
     * @return array
     */
    public function travel(IMappable $issuer, $x2, $y2)
    {
            // Get the issuer's current location

            $x1 = $issuer->getX();
            $y1 = $issuer->getY();

            if ($this->map->checkLegalMove($x1, $y1, $x2, $y2))
            {
                $result = $this->map->mapTravel($issuer, $x2, $y2);
                return $this->getResult('Success', $result);
            } else {
                return $this->getResult('Illegal move', $x2. ', ' .$y2. ' is too far or is not passable');
            }
    }

    /**
     * @param IDepotHaver $issuer
     * @param City $city
     * @param $good
     * @param $amt
     * @return array
     */
    public function sellGoods(IDepotHaver $issuer, $city, $good, $amt)
    {
        $depot = new Depot($issuer->getDepot());
        $depot->setDb($this->db);
        $depot->load();

        $tgp = new TradegoodPlatonic($good);
        $tgp->setDb($this->db);
        $tgp->load();

        $city = new City($city);
        $city->setDb($this->db);
        $city->load();

        if ((empty($depot)) | (empty($tgp))) { return $this->getResult('Event failure', 'Null depot or tradegood'); }

        $currentStores = $depot->GetValueByString($tgp->named);

        // If we have sufficient goods to sell
        if ($currentStores >= $amt) {
            $depot->setValueByString($tgp->named, ($currentStores - $amt));
            $profit = $amt * $tgp->tradevalue;

            $this->SetCoins($issuer, ($issuer->getCoin() + $profit));
            $city->taxTrade($profit);
            return $this->getResult('Success', 'sold ' .$amt. ' ' .$tgp->named. ' for ' .$profit. ' coin');
        } else {
            return $this->getResult('Illegal move', 'issued an invalid request to sell goods');
        }
    }

    /**
     * @param IDepotHaver $issuer
     * @param City $city
     * @param $good
     * @param $amt
     * @return array
     */
    public function buyGoods(IDepotHaver $issuer, $city, $good, $amt)
    {
        $depot = new Depot($issuer->getDepot());
        $depot->setDb($this->db);
        $depot->load();

        $tgp = new TradegoodPlatonic($good);
        $tgp->setDb($this->db);
        $tgp->load();

        $city = new City($city);
        $city->setDb($this->db);
        $city->load();

        if ((empty($depot)) | (empty($tgp))) { return $this->getResult('Event failure', 'Null depot or tradegood'); }

        // Calculate costs, taxes, etc.
        $purse = $issuer->getCoin();
        $cost = ($amt * $tgp->tradevalue);

        // If we have sufficient coin
        if ($purse >= $cost) {
            $this->SetCoins($issuer, ($purse - $cost));
            $city->taxTrade($cost);
            $currentStores = $depot->GetValueByString($tgp->named);
            $depot->setValueByString($tgp->named, ($currentStores + $amt));
            return $this->getResult('Success', 'bought ' .$amt. ' ' .$tgp->named. ' for ' .$cost. ' coin');
        } else {
            return $this->getResult('Illegal move', 'attempted to purchase ' .$amt. ' ' .$tgp->named. ' but had insufficient coin');
        }
    }

    /*
     *
     *
     *                       PRIVATE FUNCTIONS
     *
     *
     */

    /**
     * Create a result packaged for return
     *
     * If it is the result of a successful move,
     * publish the output to all subscribers
     *
     * @param $type string
     * @param $description string
     * @return array
     */
    private function getResult($type, $description)
    {
        $result = [];
        $result['Type'] = $type;
        $result['Description'] = $description;
        $this->status = $description;
        return $result;
    }

    /**
     * @param $issuer
     * @return mixed
     */
    private function getClass($issuer)
    {
        $getclass = explode('\\', get_class($issuer));
        $name = array_pop($getclass);
        return $name;
    }

    /**
     * @param $issuer
     * @param $amt
     */
    private function SetCoins($issuer, $amt)
    {
        $query = 'UPDATE ' .strtolower($this->getClass($issuer)). ' SET coin=' .intval($amt). ' WHERE id=' .$issuer->getId(). ';';
        $this->db->setQuery($query);
        $this->db->query();
    }
}
