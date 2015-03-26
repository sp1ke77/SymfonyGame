<?php

namespace GameBundle\Game\Model;

class Mapzone extends GameEntity
{
    /**
     * @var $x int
     * @var $y int
     * @var $geotype int
     */
    protected $x;
    protected $y;
    protected $geotype;

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return mixed
     */
    public function getGeotype()
    {
        return $this->geotype;
    }

    /**
     * @param mixed $geotype
     */
    public function setGeotype($geotype)
    {
        $this->geotype = $geotype;
    }
}