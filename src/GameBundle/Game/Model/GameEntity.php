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
     * @var $id int
     */
    protected $id;

    /**
     * Components
     * @var $db DBCommon
     */
    protected $db;

    /**
     * @param $db
     */
    public function setDb($db)
    {
        $this->db = $db;
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

        foreach($properties as $node)
        {
            if($node->getName() != 'db' && $node->getName() != 'id')
            {
                $query = "SELECT * FROM " . $table . " WHERE id=" . (int)$this->getId() . ";";
                $this->db->setQuery($query);
                $this->db->query();
                $obj = $this->db->loadObject();
                $this->{$node->getName()} = $obj->{$node->getName()};
            }
        }
    }

    public function update()
    {
        $table = get_called_class();

        /** @var array $values [k=>v]::[k=the property name, v=the property value] */
        $values = array();

        $reflectionClass = new ReflectionClass($table);
        $properties = $reflectionClass->getProperties();

        foreach($properties as $node){
            $propertyName = $node->getName();
            if($propertyName != 'id' && $propertyName != 'db')
            {
                $values[$propertyName] = $this->{$propertyName};
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
            $query = 'INSERT INTO '.$table .' ('.$fields.') VALUES("'.implode('", "',$data).'")';
        }

        $this->db->setQuery($query);
        $this->db->query();
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}