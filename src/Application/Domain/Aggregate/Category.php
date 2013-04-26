<?php
/**
 * Task Manager
 *
 * @author Cédric Derue <cedric.derue@gmail.com>
 */

namespace Application\Domain\Aggregate;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator\NotEmpty;

/** @ODM\Document(collection="categories", repositoryClass="Application\Data\CategoryRepository") */
class Category extends Entity implements ValidatableObjectInterface {
	/**
	 * Nom
	 * 
	 * @var string
	 * @ODM\String
	 */
	protected $name;
	
	/**
	 * Description
	 *
	 * @var string
	 * @ODM\String
	 */
	protected $description;
	
	/**
	 * Propriétaire
	 *
	 * @var string
	 * @ODM\ReferenceOne(targetDocument="Application\Domain\Aggregate\User", simple=true)
	 */
	protected $owner;
	
	/**
	 * Erreurs de validation
	 *
	 * @var array
	 */
	protected $errors;
	
	/**
	 * Retourne le nom
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Affecte le nom
	 * 
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	
	/**
	 * Retourne la description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * Affecte la description
	 *
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
	
	/**
	 * Retourne le propriétaire
	 *
	 * @return string
	 */
	public function getOwner() {
		return $this->owner;
	}
	
	/**
	 * Affecte le propriétaire
	 *
	 * @param string $owner
	 */
	public function setOwner($owner) {
		$this->owner = $owner;
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
		$this->name 		= (isset($data['name'])) ? $data['name'] : null;
		$this->description  = (isset($data['description'])) ? $data['description'] : null;
		$this->owner 		= (isset($data['owner'])) ? $data['owner'] : null;
	}
	
	/**
	 * Convertit l'objet courant en tableau
	 * 
	 * @return array
	 */
	public function toArray() {
		return array(
				'id' 		  => $this->getId(),
				'name' 		  => $this->getName(),
				'description' => $this->getDescription(),
				'owner'		  => $this->getOwner(),
		);
	}
	
	/**
	 * Valide les données de l'objet
	 *
	 * @return boolean
	 */
	public function validate() {
		$this->errors = array();
		$data = $this->toArray();
	
		$inputFilter = new InputFilter();
	
		$nameInput = new Input('name');
		$nameInput->getFilterChain()
				  ->attachByName('stringtrim')
				  ->attachByName('striptags');
		$nameInput->getValidatorChain()
				  ->addValidator(new NotEmpty());
	
		$descriptionInput = new Input('name');
		$descriptionInput->getFilterChain()
				  		 ->attachByName('stringtrim')
				  		 ->attachByName('striptags');
	
		$inputFilter->add($nameInput)
					->add($descriptionInput);
		
		$inputFilter->setData($data);
		return $inputFilter->isValid();
	}
	
	/**
	 * Retourne les erreurs de validation
	 *
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}
}