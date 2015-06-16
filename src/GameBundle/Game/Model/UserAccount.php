<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/18/2015
 * Time: 9:44 PM
 */

namespace GameBundle\Game\Model;

/**
 * Class User
 * @package GameBundle\Game
 */
class UserAccount extends GameEntity
{
    /**
     * @var string $username
     * @var string $email
     * @var string $password
     * @var int $characterid
     */
    protected $username;
    protected $email;
    protected $password;
    protected $characterid;

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