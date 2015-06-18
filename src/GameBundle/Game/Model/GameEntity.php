<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 3/24/2015
 * Time: 8:36 PM
 */
namespace GameBundle\Game\Model;
use ReflectionClass;
use GameBundle\Game\DBCommon;

abstract class GameEntity
{

    /**
     * Keys
     * @var int $id
     */
    protected $id;

    /**
     * Components
     * @var DBCommon $db
     */
    protected $db;

    /**
     * @var string $status
     */
    protected $status;

    /**
     * @param $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    public function getDb($db)
    {
        return $this->db;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Constructor
     * @param $id
     */
    public function __construct($id)
    {
        if (!isset($id))
        {
            return;
        } else {
            $this->id = $id;
        }
    }

    public function load()
    {
        $called_class = get_called_class();
        $class_name = explode('\\', get_class($this));
        $table = strtolower(array_pop($class_name));

        $reflectionClass = new ReflectionClass($called_class);
        $properties = $reflectionClass->getProperties();

        $query = "SELECT * FROM " . $table . " WHERE id=" . (int)$this->getId() . ";";
        $this->db->setQuery($query);
        $this->db->query();
        $obj = $this->db->loadObject();

        foreach($properties as $node)
        {
            $property = strtolower($node->getName());
            if($property != 'db' && $property != 'id' && $property != 'status')
            {
                $this->{$property} = $obj->{$property};
            }
        }
    }

    public function update()
    {
        $called_class = get_called_class();
        $reflectionClass = new ReflectionClass($called_class);
        $properties = $reflectionClass->getProperties();

        $class_name = explode('\\', get_class($this));
        $table = strtolower(array_pop($class_name));

        /** @var array $values [k=>v]::[k=the property name, v=the property value] */
        $values = array();

        foreach($properties as $node){
            $property = strtolower($node->getName());
            if($property != 'db' && $property != 'status')
            {
                $values[$property] = $this->{$property};
            }
        }

        if (isset($this->id)) {
            $query = 'UPDATE '.$table.' SET ';

            foreach($values as $field => $data)
            {
                if(is_bool($data)){
                    $data = (int) $data;
                }
                $query .= ' '.$field.' = "'.$data.'", ';
            }

            $query = substr($query, 0, -2);
            $query .= ' WHERE id = '.$this->id;
        } else {
            $keys = array_keys($values);
            $fields = implode(', ', $keys);
            $data = array_values($values);

            //insert query
            $query = 'INSERT INTO game.'.$table .' ('.$fields.') VALUES("'.implode('", "',$data).'")';
        }

        $this->db->setQuery($query);
        $this->db->query();
    }
}