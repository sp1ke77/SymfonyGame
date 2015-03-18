<?php

namespace GameBundle\Services;
use GameBundle\Game\DBCommon;
use \Exception as Exception;

/**
 * Class LoginService
 * @package GameBundle\Service
 */
class LoginService
{
    /**
     * @var DBCommon $db
     */
    protected $db;
    /**
     * @param DBCommon $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    function doLoginRequest($username, $password)
    {
        // Check if this is in the DB or not.
        $query = 'select * from game.user where username = "' . $username . '" and password = "' . $password . '"';
        $this->db->setQuery($query);
        $loginObj = $this->db->query();

        if (!empty($loginObj))
        {
            return $loginObj;
        } else {
            return null;
        }

    }
}