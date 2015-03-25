<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/24/2015
 * Time: 10:56 PM
 */
namespace GameBundle\Game\Rules\Interfaces;

interface ICombatant
{
    public function getId();
    public function getFighters();
    public function getMorale();
    public function Attack();
    public function Defend($attack);
}