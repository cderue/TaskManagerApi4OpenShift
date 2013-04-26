<?php

namespace Application\Domain\Aggregate;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\EmbeddedDocument */
class Keys extends ValueObject {
	/**
	 * Clé publique
	 * 
	 * @var string
	 * @ODM\String
	 */
	protected $access;
	
	/**
	 * Clé privée
	 * 
	 * @var string
	 * @ODM\String
	 */
	protected $secret;
	
	/**
	 * Constructeur
	 *
	 * @param string $access
	 * @param string $secret
	 */
	public function __construct($access, $secret) {
		$this->access = $access;
		$this->secret = $secret;
	}
	
	/**
	 * Retourne la clé publique
	 * 
	 * @return string
	 */
	public function getAccess() {
		return $this->access;
	}

	/**
	 * Retourne la clé privée
	 * 
	 * @return string
	 */
	public function getSecret() {
		return $this->secret;
	}
	
	/**
	 * Convertit l'objet courant en tableau
	 */
	public function toArray() {
		return array(
			'access' => $this->getAccess(),
			'secret' => $this->getSecret(),	
		);
	}
}