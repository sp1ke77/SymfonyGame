<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/24/2015
 * Time: 10:55 PM
 */
namespace GameBundle\Game\Rules;
use GameBundle\Game\Rules\Interfaces\IMappable;
use GameBundle\Game\DBCommon;

class Actions
{
    /** @var $db DBCommon */
    protected $db;

    public function __construct(DBCommon $db)
    {
        $this->db = $db;
    }

    public function mapTravel(IMappable $issuer, $x2, $y2)
    {
        $tablename = $this->getClass($issuer);
        $query = 'UPDATE ' . strtolower($tablename) . ' WHERE id=' . $issuer->getId() . ' SET x=' . (int)$x2 . ', SET  y=' . (int)$y2 . ';';
        $this->db->setQuery($query);
        $this->db->query();
        $tablename = $this->getClass($issuer);
        $result = $tablename . $issuer->getId() . ' traveled to ' . $x2 . ', ' . $y2;
        return $result;
    }

    /*
     *
     *
     *          PRIVATE FUNCTIONS
     *
     *
     */
    private function getClass($issuer)
    {
        $getclass = explode('\\', get_class($issuer));
        $name = array_pop($getclass);
        return $name;
    }
}