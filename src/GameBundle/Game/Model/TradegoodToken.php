<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 4/3/2015
 * Time: 2:04 PM
 */

namespace GameBundle\Game\Model;

class TradegoodToken extends GameEntity
{
    protected $id;
    protected $mapzone;
    protected $tg;

    /**
     * @return mixed
     */
    public function getMapzone()
    {
        return $this->mapzone;
    }

    /**
     * @param mixed $mapzone
     */
    public function setMapzone($mapzone)
    {
        $this->mapzone = $mapzone;
    }

    /**
     * @return mixed
     */
    public function getTg()
    {
        return $this->tg;
    }

    /**
     * @param mixed $tg
     */
    public function setTg($tg)
    {
        $this->tg = $tg;
    }
}