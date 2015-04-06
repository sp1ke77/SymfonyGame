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
use GameBundle\Game\Model\TradegoodPlatonic;
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

                return 'Clan' .$clan->getId(). ' explored ' .$clan->getX(). ', ' .$clan->getY();

            case 'working':

                // If I am producing anything, produce one and deposit it
                // into my depot. If I am full up, change activity to
                // 'trading'

                $tgp = new TradegoodPlatonic($clan->getProducing());
                $tgp->setDb($this->db);
                $tgp->load();
                $depot = new Depot($clan->getDepot());
                $depot->setDb($this->db);
                $depot->load();
                $depot->Produce(strtolower($tgp->getNamed()));

                $result = 'Clan' .$clan->getId(). ' produced ' .$tgp->getNamed(). ' in ' .$clan->getX(). ', ' .$clan->getY();

                if ($depot->CheckOne(strtolower($tgp->getNamed())) >= 10)
                {
                    $this->changeProducing($clan, null);
                    $this->changeActivity($clan, 'trading');
                }

                return $result;

            case 'trading':

                // Find out if I'm in range of a city; if so teleport to the nearest
                $city = $this->map->findNearestCity($clan->getX(), $clan->getY());
                if (isset($city))
                {
                    if (($city->getX() == $clan->getX()) && ($city->getY() == $clan->getY())) {
                        $depot = new Depot($clan->getDepot());
                        $depot->setDb($this->db);
                        $depot->load();

                        // If I have any nonfood goods, sell them
                        $profit = $depot->sellAllNonfood();
                        $result = 'Clan' .$clan->getId() . ' traded for ' .$profit. ' coin in the market of ' .$city->getNamed();

                        $depot->buyAllFood($clan->getCoin());
                        $depot->update();

                        $clan->setCoin($clan->getCoin() + $profit);
                        $clan->update();

                        if ($clan->getFood() >= 35) { $this->changeActivity($clan, 'celebrating'); }
                        if ($clan->getCoin() <= 5) { $this->changeActivity($clan, 'wandering'); }

                        return $result;
                    } else {
                        $this->map->teleportCity($clan, $city);
                        return 'Clan' . $clan->getId() . ' traveled to ' . $city->getNamed();
                    }
                } else {
                    $this->changeActivity($clan, 'wandering');
                    break;
                }

            case 'celebrating':

                $depot = new Depot($clan->getDepot());
                $depot->setDb($this->db);
                $depot->load();

                // Gather all the perishables into the larder, so to speak
                $clan->setFood(($depot->getWheat() + $depot->getCattle() + $depot->getFish() + $depot->getOlives()));
                $depot->setWheat(0);
                $depot->setCattle(0);
                $depot->setOlives(0);
                $depot->setFish(0);
                $depot->update(0);

                // If we can afford to, let's party
                if ($clan->getFood() >= 35) {
                    $clan->setFood($clan->getFood() - 5);
                    $clan->setPopulation($clan->getPopulation() + 10);
                    $clan->update();
                    return 'Clan ' .$clan->getId(). ' celebrated a holy day';
                }

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

    private function getClass($issuer)
    {
        $getclass = explode('\\', get_class($issuer));
        $name = array_pop($getclass);
        return $name;
    }
}