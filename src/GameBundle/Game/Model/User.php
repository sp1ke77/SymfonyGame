<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/18/2015
 * Time: 9:44 PM
 */

namespace GameBundle\Game\Model;

use \Exception as Exception;

/**
 * Class User
 * @package GameBundle\Game
 */
class User
{
    /**
     * @var DBCommon $db
     */
    protected $db;

    /**
     * @var int $userid
     */
    protected $userid;

    protected $username;

    /**
     * @var string $int
     */
    protected $name;
    /**
     * @var string $email
     */
    protected $email;
    /**
     * @var
     */
    protected $dateofbirth;
    /**
     * @var string $password
     */
    protected $password;

    /**
     * @param int $userid
     */
    public function __construct($userid)
    {
        if (is_null($userid))
        {
            return;
        }

        $this->userid = $userid;
    }

    /**
     * @param DBCommon $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function load()
    {
        if (!isset($this->db))
        {
            throw new Exception('App is trying to query but User.php db variable is not set.');
        }
        $query = 'select * from game.user where userid = "' . $this->userid . '";';
        $this->db->setQuery($query);
        $userObj = $this->db->loadObject();

        $this->username = $userObj->username;
        $this->name = $userObj->displayname;
        $this->email = $userObj->email;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function insert()
    {
        if (!isset($this->db))
        {
            throw new Exception('App is trying to query but User.php db container is not set.');
        }

        // UPCOMING -- checks for username uniqueness before inserting

        $query = 'INSERT INTO game.user (username, displayname, password, email, date_of_birth)
                      VALUES("' . $this->username . '", "' . $this->name . '", "' . $this->password . '", "'
                                    . $this->email . '",  "' . $this->dateofbirth . '");';
        $this->db->setQuery($query);
        $this->db->query();
        $this->userid = $this->db->getLastInsertId();
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return mixed
     */
    public function getDateofbirth()
    {
        return $this->dateofbirth;
    }

    /**
     * @param mixed $dateofbirth
     */
    public function setDateofbirth($dateofbirth)
    {
        $this->dateofbirth = $dateofbirth;
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