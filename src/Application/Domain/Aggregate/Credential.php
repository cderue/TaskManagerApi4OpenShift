<?php

namespace Application\Domain\Aggregate;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\EmbeddedDocument */
class Credential extends ValueObject {
	/**
	 * Nom du compte utilisateur
	 *
	 * @var string
	 * @ODM\String
	 */
	protected $username;
	
	/**
	 * Mot de passe
	 *
	 * @var string
	 * @ODM\String
	 */
	protected $password;
	
	/**
	 * Constructeur
	 * 
	 * @param string $username
	 * @param string $password
	 */
	public function __construct($username, $password) {
		$this->username = $username;
		$this->password = md5($password);
	}
	
	/**
	 * Retourne le nom de compte utilisateur
	 * 
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * Retourne le mot de passe haché
	 * 
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
     * Convertit l'objet courant en tableau
     */
	public function toArray() {
		return array(
			'username' => $this->getUsername(),
			'password' => $this->getPassword(),
		);
	}
}