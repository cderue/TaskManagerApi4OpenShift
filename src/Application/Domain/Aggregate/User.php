<?php

namespace Application\Domain\Aggregate;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator\NotEmpty;
use Zend\Validator\EmailAddress;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="users", repositoryClass="Application\Data\UserRepository") */
class User extends Entity implements ValidatableObjectInterface {
	/**
	 * Nom complet
	 *
	 * @var string
	 * @ODM\String
	 */
	protected $fullname;
	
	/**
	 * Courriel
	 *
	 * @var string
	 * @ODM\String
	 */
	protected $email;
	
	/**
	 * Compte utilisateur
	 *
	 * @var Credential
	 * @ODM\EmbedOne(targetDocument="Application\Domain\Aggregate\Credential")
	 */
	protected $credential;
	
	/**
	 * Paire de clés privée / publique
	 * 
	 * @var Keys
	 * @ODM\EmbedOne(targetDocument="Application\Domain\Aggregate\Keys")
	 */
	protected $keys;
	
	/**
	 * Erreurs de validation
	 *
	 * @var array
	 */
	protected $errors;
	
	/**
	 * Retourne le nom complet
	 * 
	 * @return string
	 */
	public function getFullname() {
		return $this->fullname;
	}

	/**
	 * Affecte le nom complet
	 * 
	 * @param string $fullname
	 */
	public function setFullname($fullname) {
		$this->fullname = $fullname;
	}

	/**
	 * Retourne le courriel
	 * 
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Affecte le courriel
	 * 
	 * @param string $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * Retourne le compte utilisateur
	 * 
	 * @return Credential
	 */
	public function getCredential() {
		return $this->credential;
	}

	/**
	 * Affecte le compte utilisateur
	 * 
	 * @param \Application\Domain\Aggregate\Credential $credential
	 */
	public function setCredential($credential) {
		$this->credential = $credential;
	}

	/**
	 * Retourne la paire de clé privée / publique
	 * 
	 * @return Keys
	 */
	public function getKeys() {
		return $this->keys;
	}

	/**
	 * Affecte la paire de clé privée / publique
	 * 
	 * @param \Application\Domain\Aggregate\Keys $keys
	 */
	public function setKeys(Keys $keys) {
		$this->keys = $keys;
	}
	
	/**
	 * Génère automatiquement une paire de clés privée / publique
	 */
	public function autoGenerateKeys() {
		$random = rand();
		$publicKey = base64_encode($random);
		$privateKey = sha1($random);
		
		$keys = new Keys($publicKey,$privateKey);
		$this->setKeys($keys);
	}

	/**
	 * Initialise l'objet depuis un tableau
	 *
	 * @param array $data
	 */
	public function exchangeArray(array $data) {
		$this->id     	  = (isset($data['id'])) ? $data['id'] : null;
		$this->fullname   = (isset($data['fullname'])) ? $data['fullname'] : null;
		$this->email 	  = (isset($data['email'])) ? $data['email'] : null;
		if (isset($data['credential']) && $data['credential'] instanceof Credential) {
			$this->credential = $data['credential'];
		} elseif (isset($data['credential']) && is_array($data['credential'])) {
			$username = (isset($data['credential']['username'])) ? $data['credential']['username'] : null;
			$password = (isset($data['credential']['password'])) ? $data['credential']['password'] : null;
			$this->credential = new Credential($username, $password);
		} else {
			$this->credential = null;
		}
		if (isset($data['keys']) && $data['keys'] instanceof Keys) {
			$this->keys = $data['keys'];
		} elseif (isset($data['keys']) && is_array($data['keys'])) {
			$access = (isset($data['keys']['access'])) ? $data['keys']['access'] : null;
			$secret = (isset($data['keys']['secret'])) ? $data['keys']['secret'] : null;
			$this->keys = new Keys($access, $secret);
		} else {
			$this->keys = null;
		}
	}

	/**
     * Convertit l'objet courant en tableau
     */
	public function toArray() {
		return array(
			'id' => $this->getId(),
			'fullname' => $this->getFullname(),
			'email' => $this->getEmail(),
			'credential' => (null !== $this->getCredential())?$this->getCredential()->toArray():null,
			'keys' => (null !== $this->getKeys())?$this->getKeys()->toArray():null,
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
		
		$fullnameInput = new Input('fullname');
		$fullnameInput->getFilterChain()
		->attachByName('stringtrim')
		->attachByName('striptags');
		$fullnameInput->getValidatorChain()
		->addValidator(new NotEmpty());
		
		$emailInput = new Input('email');
		$emailInput->getValidatorChain()
		->addValidator(new EmailAddress());
		
		$credentialInput = new Input('credential');
		$credentialInput ->getValidatorChain()
		->addValidator(new CredentialValidator());
		
		$inputFilter->add($fullnameInput)
		->add($emailInput)
		->add($credentialInput);
		
		$inputFilter->setData($data);
		$isValid = $inputFilter->isValid();
		if (!$isValid) {
			foreach ($inputFilter->getInvalidInput() as $error) {
				$this->errors[] = $error->getMessages() ;
			}
		}
		return $isValid ;
	}
	
	public function getErrors() {
		return $this->errors;
	}
}