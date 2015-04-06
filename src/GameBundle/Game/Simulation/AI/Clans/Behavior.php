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
use GameBundle\Game\Model\TradegoodToken;
use GameBundle\Game\Rules\Rules;
use GameBundle\Services\MapService;
use GameBundle\Game\Rules\Interfaces\IDepotHaver;
use GameBundle\Game\Model\Depot;

class Behavior
{

    /** @var $db DBCommon */
    protected $db;
    protected $rules;
    protected $news;
    protected $map;

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function __construct($db)
    {
        $this->db = $db;
        $this->rules = new Rules();
        $this->rules->setDb($this->db);
        $this->map = new MapService();
        $this->map->setDb($this->db);
    }

    // Behavior is a strategy pattern based on a set of very simple state changes

    // The optimal route is: wander -> explore -> work -> seek to trade -> trade -> holiday

    public function TakeAction($clanId)
    {
        $clan = new Clan($clanId);
        $clan->setDb($this->db);
        $clan->load();

        $action = [];

        switch ($clan->getActivity())
        {
            case 'wandering':
                $action['Action'] = 'travel';
                $action['Issuer'] = $clan;
                $mz = $this->map->GetARandomMove($clan);
                $action['Args'] = $mz->getX(). ',' .$mz->getY();
                $result = $this->rules->submit($action);

                if ($result['Type'] == 'Success') {
                    $test[] = $result['Description'];
                } else {
                    $test[] = $result['Description'];
                }

                if (rand(1,3) == 3)
                {
                    $result['Description'] .= ' and began exploring';
                    $this->changeActivity($clan, 'exploring');
                }

                return $result;

            case 'exploring':

                $mz = $this->map->getMapzoneFromAbstract($clan->getX(), $clan->getY());
                $producing = $this->map->exploreForTrade($mz);

                if (is_null($producing)) {
                    $this->changeActivity($clan, 'wandering');
                } else {
                    $this->changeProducing($clan, $producing);
                    $this->changeActivity($clan, 'working');
                }

                return null;

            case 'working':
                // If I am producing anything, produce one and deposit it
                // into my depot. If I am full up, change activity to
                // 'seeking to trade'

                break;

            case 'seeking to trade':
                // If I am within range of a city, teleport there
                // and change activity to 'trading'

                break;
            case 'trading':
                // If I have any nonfood goods, sell them

                // If I have any spare coin, buy food

                // If buying food leaves me with sufficient food, change
                // activity to 'celebrating' otherwise change to 'wandering'

                break;
            case 'celebrating':
                // Consume 5 food and prouce 5 population
                // If (population >= 250 && Odds(population / 10))
                // {
                //     population -= 100;
                //     create a new tribe based on this one;
                // }

                break;
            default:
                // Nothing

                break;
        }
    }

    public function checkLarder($clanId)
    {
        $clan = new Clan($clanId);
        $clan->setDb($this->db);
        $clan->load();

        $depot = New Depot($clan->getDepot());
        $depot->load();

        // This should be made more extensible, probably by using reflection some more
        $clan->setFood($clan->getFood() + (($depot->getWheat() * 1) + ($depot->getOlives() * 1.2) +
            ($depot->getFish() * 1.2) + ($depot->getCattle() * 2.8)));
        $depot->setWheat(0);
        $depot->setOlives(0);
        $depot->setFish(0);
        $depot->setCattle(0);
        $depot->update();
    }

    public function consumeFood($amt, $clanId)
    {
        $clan = new Clan($clanId);
        $clan->setDb($this->db);
        $clan->load();

        if ($clan->getFood() > $amt) {
            $clan->setFood($clan->getFood() - $amt);
            $clan->update();
            return true;
        } else {
            // Generate some news about famine and bad shit
            $clan->setPopulation($clan->getPopulation() - 2);
            $clan->update();
            return false;
        }
    }

    public function changeActivity(Clan &$clan, $activity)
    {
        $query = 'UPDATE clan SET activity="' .$activity. '" WHERE id=' .$clan->getId(). ';';
        $this->db->setQuery($query);
        $this->db->query();
    }

    public function changeProducing(Clan &$clan, $producing)
    {
        $query = 'UPDATE clan SET producing="' .$producing. '" WHERE id=' .$clan->getId(). ';';
        $this->db->setQuery($query);
        $this->db->query();
    }
}