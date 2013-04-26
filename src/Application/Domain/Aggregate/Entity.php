<?php
/**
 * Task Manager
 *
 * @author Cédric Derue <cedric.derue@gmail.com>
 */

namespace Application\Domain\Aggregate;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\MappedSuperclass
 *
 */
abstract class Entity
{
    /**
     * Identifiant unique
     * 
     * @var int
     * @ODM\Id
     */
    protected $id = null;
    
    /**
     * Retourne l'identifiant unique
     * 
     * @return string
     */
    public function getId()
    {
    	return $this->id;
    }
    
    /**
     * Affecte l'identifiant unique
     * 
     * @param string $id
     */
    public function setId($id)
    {
    	$this->id = $id;
    	return $this;
    }
    
    /**
     * Retourne la valeur d'une propriété
     *
     * @param string $name
     * @throws \Exception
     */
    public function __get($name)
    {
    	$method = 'get' . $name;
    	if (!method_exists($this, $method)) {
    		throw new \Exception('Invalid entity property');
    	}
    	return $this->$method();
    }
    
    /**
     * Affecte la valeur d'une propriété
     * 
     * @param string $name
     * @param mixed $value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (!method_exists($this, $method)) {
            throw new \Exception('Invalid entity property');
        }
        $this->$method($value);
    }
    
    /**
     * Détermine si l'entité est nouvelle ou persistante
     * 
     * @return boolean
     */
    public function isTransient() {
        return empty($this->id);
    }
    
    /**
     * Teste si l'objet passé en paramètre
     * est égal à l'entité courante
     * 
     * @param mixed $object
     * @return boolean
     */
    public function equals($object)
    {
        if ($object === null || !($object instanceof  Entity))
            return false;
    
        if ($this === $object)
            return true;
    
        if ($object->isTransient() || $this->isTransient())
            return false;
        else
            return $object->getId() === $this->getId();
    }
    
    /**
     * Initialise l'objet depuis un tableau
     * 
     * @param array $data
     */
    public abstract function exchangeArray(array $data);
    
    /**
     * Convertit l'objet courant en tableau
     */
    public abstract function toArray();
}