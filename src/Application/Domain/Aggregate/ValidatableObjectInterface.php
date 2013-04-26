<?php
/**
 * Task Manager
 *
 * @author Cédric Derue <cedric.derue@gmail.com>
 */

namespace Application\Domain\Aggregate;

interface ValidatableObjectInterface {
	/**
	 * Valide les données de l'objet qui implémente l'interface
	 */
	public function validate();
	
	/**
	 * Retourne les erreurs de validation
	 */
	public function getErrors();
}