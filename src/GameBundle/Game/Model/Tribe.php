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
    protected $gregariousness;
    protected $insularity;
    protected $belligerence;
    protected $tenacity;
    protected $spirituality;
    protected $sumptuousness;

    /**
     * @return mixed
     */
    public function getGregariousness()
    {
        return $this->gregariousness;
    }

    /**
     * @param mixed $gregariousness
     */
    public function setGregariousness($gregariousness)
    {
        $this->gregariousness = $gregariousness;
    }

    /**
     * @return mixed
     */
    public function getInsularity()
    {
        return $this->insularity;
    }

    /**
     * @param mixed $insularity
     */
    public function setInsularity($insularity)
    {
        $this->insularity = $insularity;
    }

    /**
     * @return mixed
     */
    public function getBelligerence()
    {
        return $this->belligerence;
    }

    /**
     * @param mixed $belligerence
     */
    public function setBelligerence($belligerence)
    {
        $this->belligerence = $belligerence;
    }

    /**
     * @return mixed
     */
    public function getTenacity()
    {
        return $this->tenacity;
    }

    /**
     * @param mixed $tenacity
     */
    public function setTenacity($tenacity)
    {
        $this->tenacity = $tenacity;
    }

    /**
     * @return mixed
     */
    public function getSpiritualty()
    {
        return $this->spiritualty;
    }

    /**
     * @param mixed $spiritualty
     */
    public function setSpiritualty($spiritualty)
    {
        $this->spiritualty = $spiritualty;
    }

    /**
     * @return mixed
     */
    public function getSumptuousness()
    {
        return $this->sumptuousness;
    }

    /**
     * @param mixed $sumptuousness
     */
    public function setSumptuousness($sumptuousness)
    {
        $this->sumptuousness = $sumptuousness;
    }

    /**
     * @param mixed $culture
     */
    public function setCulture($culture)
    {
        $this->culture = $culture;
    }

    /**
     * @param mixed $named
     */
    public function setNamed($named)
    {
        $this->named = $named;
    }


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