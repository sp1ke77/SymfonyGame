<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/23/2015
 * Time: 1:23 PM
 */

namespace GameBundle\Game\Rules;
use GameBundle\Game\DBCommon;

class Rules
{

    /**
     * Properties
     * @var string $status
     */
    protected $status;

    /**
     * Components
     * @var DBCommon $db
     */
    protected $db;

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * submit() is the main input to the Rules system
     *
     * @param array $move An associative array containing arguments for the submitted action
     * @return array The outcome of the submitted action. Most recent message is also available
     * by getting $this->status.
     */
    public function submit($move)
    {

        /*
         * Rules implements a Strategy pattern. It must do three things:
         * 1. Decide what kind of game-move $arg represents
         * 2. Check if it is a legal move; if it is, execute it
         * 3. Create some news about it and output results as an array
         */

        // Validate
        if (empty($move['Action']) | empty($move['Issuer']))
        {
            return $this->getError('Invalid request', 'Missing action or issuer.');
        }

        // Get the parameters
        $action = $move['Action'];
        $issuer = (int)$move['Issuer'];

        // Get the optional arguments, if any were inputted
        if (empty($move['Args']))
        {
            $args = null;
        } else {
            $args = $move['Args'];
        }

        // Switch
        switch ($action) {
            case 'Travel':

                if (!$args) {
                    return $this->getError('Invalid request', 'Travel requires Args: string "x,y" ');
                } else {
                    $moveLocation[] = explode(',', $args);
                }

                // private function travelLogic()
                // {
                //      Get Issuer as object
                //      if it is not IMappable, $this->getError();
                //      some x,y math to see if it's a valid move;
                //      return $this->getResult();
                //  }

                break;
        }

        // Create some output
    }

    /*
     *
     *
     *              PRIVATE FUNCTIONS
     *
     *
     */

    /**
     *
     * Package an array for output
     *
     * @param string $type
     * @param string $description
     * @return array
     */
    private function getError($type, $description)
    {
        $error = [];
        $error['Type'] = $type;
        $error['Description'] = $description;
        $this->status = $description;
        return $error;
    }

    private function getResult($type, $description)
    {
        $result = [];
        $result['Type'] = $type;
        $result['Description'] = $description;
        $this->status = $description;
        return $result;
    }
}