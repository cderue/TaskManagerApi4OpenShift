<?php
/**
 * Task Manager
 *
 * @author Cédric Derue <cedric.derue@gmail.com>
 */

namespace Application\Domain\Aggregate;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\EmbeddedDocument */
class Period extends ValueObject {
    /**
     * Date de début de période
     * 
     * @var string
     * @ODM\Date
     */
    protected $beginning;
    
    /**
     * Date de fin de période
     * 
     * @var string
     * @ODM\Date
     */
    protected $end;
    
    /**
     * Constructeur
     * 
     * @param string $beginning
     * @param string $end
     */
    public function __construct(\DateTime $beginning = null, \DateTime $end = null) {
    	/*if (null === $beginning){
    		$beginning = new \DateTime();
    	}
    	if (null === $end){
    		$end = new \DateTime();
    	}*/
    	/*if (null !== $beginning && null !== $end) {
    		if ($beginning > $end) {
    			throw new \DomainException('beginning date should be inferior to end date');
    		}
    	}*/
    	
    	$this->beginning = $beginning;
        $this->end = $end;
    } 

    /**
     * Retourne la date de début de période
     * 
     * @return \DateTime
     */
    public function getBeginning() {
        return $this->beginning;
    }
    
    /**
     * Retourne la date de fin de période
     * 
     * @return \DateTime
     */
    public function getEnd() {
        return $this->end;
    }
    
    /**
     * Convertit l'objet courant en tableau
     */
    public function toArray() {
    	$copyArray = array(
    			'beginning' =>null,
    			'end' => null,
    	);
    	if (null !== $this->getBeginning()) {
    		$copyArray['beginning'] = $this->getBeginning()->format('Y-m-d');
    	}	
    	if (null !== $this->getEnd()) {
    		$copyArray['end'] = $this->getEnd()->format('Y-m-d');
    	}
    	return $copyArray;
    }
}