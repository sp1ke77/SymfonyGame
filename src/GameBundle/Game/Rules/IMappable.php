<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/23/2015
 * Time: 10:33 PM
 */

namespace GameBundle\Game\Rules;

interface IMappable
{
    public function getX();
    public function getY();
    public function setX($x);
    public function setY($y);
    public function getId();

}