<?php
/**
 * Task Manager
 *
 * @author Cédric Derue <cedric.derue@gmail.com>
 */

namespace Application\Domain\Aggregate;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\MappedSuperclass */
abstract class ValueObject
{
    /**
     * Teste si l'objet passé en paramètre 
     * est égal à l'objet courant
     * 
     * @param mixed $object
     * @return boolean
     */
	public function equals($object)
    {
        if (get_class($this) === get_class($object)) {
            $methods = get_class_methods($this);
            foreach ($methods as $method) {
                if (substr($method, 0, 3) === 'get') {
                    if ($this->$method() !== $object->$method()) {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }
    
    /**
     * Convertit l'objet courant en tableau
     */
    public abstract function toArray();
}