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

    /**
     * @param $username
     * @param $password
     * @return null|array $results
     */
    function doLoginRequest($username, $password)
    {
        // Check if this is in the DB or not.
        $query = 'select * from game.user where username = "' . $username . '" and password = "' . $password . '"';
        $this->db->setQuery($query);
        $loginObj = $this->db->loadObject();

        if (!empty($loginObj))
        {
            $results["username"] = $loginObj->username;
            $results["display name"] = $loginObj->displayname;
            $results["user id"] = $loginObj->user_id;

            return $results;
        } else {
            return null;
        }

    }
}