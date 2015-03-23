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
     * @param array $request An associative array containing arguments for the submitted action
     * @return array The outcome of the submitted action. Most recent message is also available
     * by getting $this->status.
     */
    public function submit($request)
    {
        /*
         * Rules implements a Strategy pattern. It must do three things:
         * 1. Decide what kind of game-move the request represents
         * 2. Check if it is a legal move; if it is, execute it
         * 3. Create some news about it and output results 
         */

        // Validate
        if (empty($request['Action']) | empty($request['Issuer']))
        {
            return $this->getResult('Invalid request', 'Missing action or issuer');
        }

        // Get the parameters and optional args
        $action = $request['Action'];
        $issuer = (int)$request['Issuer'];
        if (empty($request['Args']))
        {
            $args = null;
        } else {
            $args = $request['Args'];
        }

        // Pick a strategy
        switch ($action)
        {
            case 'Travel':

                if (!$args) {
                    return $this->getResult('Invalid request', 'Travel requires Args: string "x,y"');
                } else {
                    $moveLocation[] = explode(',', $args);
                }

                // something->function travelLogic()
                // {
                //      Get Issuer as object
                //      if it is not IMappable, $this->getError();
                //      some x,y math to see if it's a valid move;
                //      return $this->getResult();
                //  }

                break;

            default:

                return $this->getResult('Invalid request', 'Undefined action');
        }
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
    private function getResult($type, $description)
    {
        $error = [];
        $error['Type'] = $type;
        $error['Description'] = $description;
        $this->status = $description;
        return $error;
    }

}