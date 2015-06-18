<?php

namespace GameBundle\Services;
use GameBundle\Game\DBCommon;
use GameBundle\Game\Model\Clan;

/**
 * Class ClanService
 * @package GameBundle\Services
 */
class ClanService
{
    /**
     * @var DBCommon $db
     */
    protected $db;

    /** @param DBCommon */
    public function setDb($db)
    {
        $this->db = $db;
    }
}