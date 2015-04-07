<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/23/2015
 * Time: 1:23 PM
 */

namespace GameBundle\Game\Rules;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Rules\Interfaces\IMappable;
use GameBundle\Game\Rules\Interfaces\IDepotHaver;
use GameBundle\Game\Rules\Interfaces\ICombatant;
use GameBundle\Game\Rules\Checks;
use GameBundle\Game\Rules\Actions;
use GameBundle\Game\Model\Clan;
use GameBundle\Game\Model\Depot;


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
     * @var $db DBCommon
     * @var $checks \GameBundle\Game\Rules\Checks
     * @var $actions Actions
     */
    protected $db;
    protected $checks;
    protected $actions;

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
     * @param DBCommon $db
     */
    public function setDb($db)
    {
        $this->db = $db;
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

                break;

            case 'holiday':

                // Check if it's a Clan and if so, if it has enough food and if so, ->holiday()
                // No other class can take this action
                if ($this->getClass($issuer) != 'Clan')
                {
                    return $this->getResult('Invalid request', 'Only clans can holiday');
                } else {
                    return $this->holiday($issuer);
                }

                break;

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
                    $xy = explode(',', $args);
                    if (count($xy) != 2)
                    {
                        return $this->getResult('Invalid request', 'Buy Goods requires Args: string "{good},{amount}"');
                    }
                    return $this->buyGoods($issuer, $xy[0], $xy[1]);
                }

                // Args {good},{amount}. Note that {good} must be lowercase and must match a field in game.depot.

                break;

            case 'sell goods':

                if (!array_search("GameBundle\\Game\\Rules\\Interfaces\\IDepotHaver", class_implements($issuer)))
                {
                    return $this->getResult('Invalid request', 'Issuer must implement IDepotHaver');
                }

                if (!$args) {
                    return $this->getResult('Invalid request', 'Sell Goods requires Args: string"');
                } else {
                    $xy = explode(',', $args);
                    if (count($xy) != 2)
                    {
                        return $this->getResult('Invalid request', 'Sell Goods requires Args: string "{good},{amount}"');
                    }
                    return $this->buyGoods($issuer, $xy[0], $xy[1]);
                }

                // Args {good},{amount}. Note that {good} must be lowercase and must match a field in game.depot.

                break;

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

    public function travel(IMappable $issuer, $x2, $y2)
    {
            $checks = new Checks();
            $checks->setDb($this->db);
            $actions = new Actions();
            $actions->setDb($this->db);

            // Get the issuer's current location

            $x1 = $issuer->getX();
            $y1 = $issuer->getY();

            if ($checks->checkLegalMove($x1, $y1, $x2, $y2))
            {
                $result = $actions->mapTravel($issuer, $x2, $y2);
                return $this->getResult('Success', $result);
            } else {
                return $this->getResult('Illegal move', $x2. ', ' .$y2. ' is too far or is not passable');
            }
    }

    public function holiday(Clan $issuer) {

            if ($issuer->getFood() > 5)
            {
                $issuer->checkLarder();
                if ($issuer->consumeFood($issuer, 5)) {}
                    $issuer->setPopulation($issuer->getPopulation() + 5);
                    return $this->getResult('Success', 'Clan ' . $issuer->getId() . ' celebrated a holy day');
            } else {
                return $this->getResult('Illegal move', 'Cannot holiday without at least 5 food');
            }
    }

    public function buyGoods(IDepotHaver $issuer, $args)
    {
        return $this->getResult('Success', 'Request reached the end of the path');
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

    private function getClass($issuer)
    {
        $getclass = explode('\\', get_class($issuer));
        $name = array_pop($getclass);
        return $name;
    }
}
