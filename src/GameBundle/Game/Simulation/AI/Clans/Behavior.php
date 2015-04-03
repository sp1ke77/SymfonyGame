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
        $news = new NewsService();
        $clan = new Clan($clanId);
        $clan->load();

        $action = [];

        switch ($clan->getActivity())
        {
            case 'wandering':
                $action['Action'] = 'travel';
                $action['Issuer'] = &$clan;
                $mz = $this->GetARandomMove($clan);
                $action['Args'] = $mz->x. ',' .$mz->y;

                $news->createSomeNews($rules->submit($action), $mz->x, $mz->y);
            break;

            default:
                // Nothing

                break;
        }
    }


}