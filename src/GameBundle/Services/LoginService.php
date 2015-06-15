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
        $query = 'select * from game.useraccount where username = "' . $username . '" and password = "' . $password . '"';
        $this->db->setQuery($query);
        $loginObj = $this->db->loadObject();

        if (!empty($loginObj))
        {
            $results["logged in"] = true;
            $results["username"] = $loginObj->username;
            $results["user id"] = $loginObj->id;
        } else {
            $results["logged in"] = false;
        }
        return $results;
    }

    function checkForCharacter($userid) {

        // Check if this is in the DB or not.
        $query = 'select * from game.useraccount where id = "' . $userid . '";';
        $this->db->setQuery($query);
        $userObj = $this->db->loadObject();

        if (!empty($userObj)) {
            return $userObj->characterid;
        } else {
            return null;
        }
    }

    function createNewUser($username, $password, $email)
    {
        $query = 'INSERT INTO game.useraccount(username, password, email) VALUES("' . $username . '", "' .$password. '", "' .$email. '");';
        $this->db->setQuery($query);
        $obj = $this->db->query();
        return $obj;            // Boolean
    }
}