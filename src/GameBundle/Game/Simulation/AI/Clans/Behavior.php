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
use GameBundle\Game\Rules\Rules;
use GameBundle\Services\MapService;
use GameBundle\Services\NewsService;

class Behavior
{

    /** @var $db DBCommon */
    protected $db;

    public function setDb($db)
    {
        $this->db = $db;
    }

    // Behavior is a strategy pattern based on a set of very simple state changes

    // The optimal route is: wander -> explore -> work -> seek to trade -> trade -> holiday

    public function TakeAction($clanId)
    {
        $rules = new Rules();
        $rules->setDb($this->db);
        $news = new NewsService();
        $news->setDb($this->db);
        $map = new MapService();
        $map->setDb($this->db);
        $clan = new Clan($clanId);
        $clan->setDb($this->db);
        $clan->load();

        $action = [];

        switch ($clan->getActivity())
        {
            case 'wandering':
                $action['Action'] = 'travel';
                $action['Issuer'] = $clan;
                $mz = $map->GetARandomMove($clan);
                $action['Args'] = $mz->getX(). ',' .$mz->getY();
                $result = $rules->submit($action);

                if ($result['Type'] == 'Success') {
                    $test[] = $result['Description'];
                } else {
                    $test[] = $result['Description'];
                }
                return $result;
            break;

            default:
                // Nothing

                break;
        }
    }


}