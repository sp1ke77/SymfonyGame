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

    /*
     *
     *
     *
     *
     *                      MAIN
     *
     *
     *
     *
     */
    /**
     * Create a request packaged for submission
     *
     * @param string $action
     * @param string $issuer
     * @param string $args
     * @return array
     */
    public function createRequest($issuer, $action, $args = null)
    {
        $request = [];
        $request['Action'] = $action;
        $request['Issuer'] = $issuer;
        $request['Args'] = $args;
        return $request;
    }

    /**
     * @param array $request an array containing arguments for the submitted request
     * @return array the outcome of the request
     */
    public function submit($request)
    {
        // Validate the request object
        if (empty($request['Action']) | empty($request['Issuer'])) {
            return $this->getResult('Invalid request', 'Missing action or issuer');
        }

        // Get the parameters and optional args
        $action = $request['Action'];
        $issuer = $request['Issuer'];
        if (empty($request['Args'])) {
            $args = null;
        } else {
            $args = $request['Args'];
        }

        // Pick a strategy
        switch ($action) {
            case 'Travel':

                if (!$args) {
                    return $this->getResult('Invalid request', 'Travel requires Args: string "x,y"');
                } else {
                    $xy[] = explode(',', $args);
                    $this->travel($issuer, $xy[0], $xy[1]);
                }

                break;

            default:

                return $this->getResult('Invalid request', 'Undefined action');
        }
    }

    /*
     *
     *
     *
     *                      STRATEGIES
     *
     *
     *
     */

    protected function travel($issuer, $x2, $y2)
    {
        if (array_search('GameBundle\Game\Rules\IMappable', class_implements($issuer))) {
            $x1 = $issuer->getX();
            $y1 = $issuer->getY();
            $tablename = strtolower(basename(get_class($issuer)));

            if ($this->checkLegalMove($x1, $y1, $x2, $y2)) {
                $query = 'UPDATE ' . $tablename . ' SET x=' . (int)$x2 . ', y=' . (int)$y2 . ' WHERE id=' . $issuer->getId() . ';';
                $this->db->setQuery($query);
                $this->db->query();
                return $this->getResult('Success', $issuer->named . ' traveled to ' . $x2 . ', ' . $y2);
            } else {
                return $this->getResult('Ilegal move', 'Destination is too far or not passable');
            }
        }
    }

    /*
     *
     *
     *                      PRIVATE FUNCTIONS
     *
     *
     *
     */

    /**
     * Create a result packaged for return
     *
     * If it is the result of a successful move,
     * publish the output to all subscribers
     *
     * @param string $type
     * @param string $description
     * @return array
     */
    private function getResult($type, $description)
    {
        $result = [];
        $result['Type'] = $type;
        $result['Description'] = $description;
        $this->status = $description;
        return $result;
    }

    /**
     * Takes x1,y1 and x2,y2, returns if move is legal
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     * @return bool
     */
    private function checkLegalMove($x1, $y1, $x2, $y2)
    {
        if (abs(($x1 - $x2) > 1) & (abs($y1 - $y2)) > 1)
        {
            $query = "SELECT geotype FROM mapzone WHERE x=" . $x2 . " AND y=" . $y2 . ";";
            $this->db->setQuery($db);
            $this->db->query();
            $result = $this->db->loadObject();
            if ($result == 'plains' | $result == 'forest' | $result == 'desert' | $result == 'hills' | $result == 'mountain')
            {
                return true;
            } else {
                return false;
            }
        }
    }



}