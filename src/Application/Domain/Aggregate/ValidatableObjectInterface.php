<?php
/**
 * Task Manager
 *
 * @author C�dric Derue <cedric.derue@gmail.com>
 */

namespace Application\Domain\Aggregate;

interface ValidatableObjectInterface {
	/**
	 * Valide les donn�es de l'objet qui impl�mente l'interface
	 */
	public function validate();
	
	/**
	 * Retourne les erreurs de validation
	 */
	public function getErrors();
}