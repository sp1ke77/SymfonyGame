<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/18/2015
 * Time: 9:44 PM
 */

namespace GameBundle\Game\Model;

use \Exception as Exception;
use GameBundle\Game\DBCommon;

/**
 * Class User
 * @package GameBundle\Game
 */
class User extends GameEntity
{
    /**
     * @var DBCommon $db
     */
    protected $db;

    protected $username;

    /**
     * @var string $email
     */
    protected $email;
    /**
     * @var string $password
     */
    protected $password;

    /**
     * @return mixed
     */
    public function getCharacterid()
    {
        return $this->characterid;
    }

    /**
     * @param mixed $characterid
     */
    public function setCharacterid($characterid)
    {
        $this->characterid = $characterid;
    }
    protected $characterid;

    /**
     * @param int $userid
     */
    public function __construct($userid)
    {
        if (is_null($userid))
        {
            return;
        }

        $this->id = $userid;
    }

    /**
     * @param DBCommon $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }


}