<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/20/2015
 * Time: 2:07 PM
 */
namespace GameBundle\Game\Model;

/**
 * Class Tribe
 * @package GameBundle\Game
 */
class Tribe extends GameEntity
{
    /**
     * Properties
     * @var int $culture
     * @var string $named
     */
    protected $culture;
    protected $named;

    /**
     * @return mixed
     */
    public function getCulture()
    {
        return $this->culture;
    }

    /**
     * @return mixed
     */
    public function getNamed()
    {
        return $this->named;
    }
}