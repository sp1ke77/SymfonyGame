<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 4/2/2015
 * Time: 1:25 AM
 */

namespace GameBundle\Services;
use GameBundle\Game\Model\News;

class NewsService
{

    protected $db;

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function createSomeNews($msg, $x, $y)
    {
        $news = new News(null);
        $news->setDb($this->db);
        $news->setText($msg);
        $news->setX($x);
        $news->setY($y);
        $news->update();
    }

}