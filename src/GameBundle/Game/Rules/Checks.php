<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/24/2015
 * Time: 10:46 PM
 */
namespace GameBundle\Game\Rules;
use GameBundle\Game\DBCommon;

class Checks
{
    /** @var $db DBCommon */
    protected $db;

    public function __construct(DBCommon $db)
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
            $query = "SELECT geotype FROM mapzone WHERE x=" . $x2 . " AND y=" . $y2 . ";";
            $this->db->setQuery($query);
            $this->db->query();
            $result = $this->db->loadResult();
            if ($result == 'plains' | $result == 'forest' | $result == 'desert' |
                $result == 'hills' | $result == 'mountain' | $result == 'swamp')
            {
                return true;
            } else {
                return false;
            }
        }
    }
}