<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 4/2/2015
 * Time: 1:25 AM
 */

namespace GameBundle\Services;
use GameBundle\Game\Model\News;
use GameBundle\Game\DBCommon;

class NewsService
{
    /** @var $db DBCommon */
    protected $db;

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function createSomeNews($msg, $x, $y)
    {
        $query = "INSERT INTO news(text, x, y) VALUES('" .$msg. "', " .$x. ", " .$y. ");";
        $this->db->setQuery($query);
        $this->db->query();
    }

}