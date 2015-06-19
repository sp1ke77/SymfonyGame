<?php

namespace GameBundle\Game\Model;

class Persona extends GameEntity
{
    /**
     * @return mixed
     */
    public function getFame()
    {
        return $this->fame;
    }

    /**
     * @param mixed $fame
     */
    public function setFame($fame)
    {
        $this->fame = $fame;
    }

    /**
     * @return mixed
     */
    public function getHonor()
    {
        return $this->honor;
    }

    /**
     * @param mixed $honor
     */
    public function setHonor($honor)
    {
        $this->honor = $honor;
    }

    /**
     * @return mixed
     */
    public function getControversy()
    {
        return $this->controversy;
    }

    /**
     * @param mixed $controversy
     */
    public function setControversy($controversy)
    {
        $this->controversy = $controversy;
    }
    protected $fame;                // symbolized by cups
    protected $honor;               // symbolized by bullsheads
    protected $controversy;         // symbolized by pigs' hooves
}