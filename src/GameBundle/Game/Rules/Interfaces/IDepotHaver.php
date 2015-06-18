<?php
namespace GameBundle\Game\Rules\Interfaces;

/**
 * Interface IDepotHaver
 * @package GameBundle\Game\Rules\Interfaces
 */
interface IDepotHaver
{
    public function getId();
    public function update();
    /**
     * @return int
     */
    public function getDepot();

    /**
     * @return int
     */
    public function getCoin();

    /**
     * @param int
     */
    public function setCoin($coin);

}