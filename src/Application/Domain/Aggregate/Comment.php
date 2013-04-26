<?php
/**
 * Task Manager
 *
 * @author Cédric Derue <cedric.derue@gmail.com>
 */

namespace Application\Domain\Aggregate;

use Application\Domain\Aggregate\Entity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\EmbeddedDocument */
class Comment extends Entity {
    /**
     * Contenu texte
     * 
     * @var string
     * @ODM\String
     */
	protected $content;
    
	/**
	 * Date de création
	 * 
	 * @var string
	 * @ODM\Date
	 */
    protected $creationDate;
    
    /**
     * Retourne le contenu du commentaire
     * 
     * @return string
     */
    public function getContent() {
        return $this->content;
    }
    
    /**
     * Affecte le contenu du commentaire
     * 
     * @param string $content
     * @return \Domain\TaskAggregate\Comment
     */
    public function setContent($content) {
    	$this->content = $content;
    	return $this;
    }

    /**
     * Retourne la date de création
     * 
     * @return string
     */
    public function getCreationDate() {
        return $this->creationDate;
    }
    
    /**
     * Affecte la date de création
     * 
     * @param string $creationDate
     * @return \Domain\TaskAggregate\Comment
     */
    public function setCreationDate($creationDate) {
    	$this->creationDate = $creationDate;
    	return $this;
    }
    
    /**
     * Initialise l'objet depuis un tableau
     *
     * @param array $data
     */
    public function exchangeArray(array $data)
    {
    	$this->id     		= (isset($data['id'])) ? $data['id'] : null;
    	$this->content 		= (isset($data['content'])) ? $data['content'] : null;
    	$this->created 		= (isset($data['created'])) ? $data['created'] : null;
    }
    
    /**
     * Convertit l'objet courant en tableau
     */
    public function toArray() {
    	return array(
    		'id' 	  => $this->getId(),
    		'content' => $this->getContent(),
    		'created' => $this->getCreated(),	
    	);
    }
}