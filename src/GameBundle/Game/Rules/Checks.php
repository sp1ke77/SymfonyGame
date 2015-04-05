<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/24/2015
 * Time: 10:46 PM
 */
namespace GameBundle\Game\Rules;
use GameBundle\Game\DBCommon;
use GameBundle\Services\MapService;

class Checks
{

    /** @var $db DBCommon */
    protected $db;

    /** @param $db DBcommon */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * Takes x1,y1 and x2,y2, returns if move is legal
     * @param $x1 int
     * @param $y1 int
     * @param $x2 int
     * @param $y2 int
     * @return bool
     */
    public function checkLegalMove($x1, $y1, $x2, $y2)
    {
        if (abs(($x1 - $x2) < 2) & (abs($y1 - $y2)) < 2)
        {
            $mapService = new MapService();
            $mapService->setDb($this->db);
            $geotype = $mapService->getGeotypeByMapzone($x2, $y2);
            if ($geotype == 'plains' | $geotype == 'forest' | $geotype == 'desert' |
                $geotype == 'hills' | $geotype == 'mountain' | $geotype == 'swamp')
            {
                return true;
            } else {
                return false;
            }
        }
    }
}