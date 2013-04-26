<?php

namespace Application\Domain\Contract;

use Application\Domain\Aggregate\User;

interface UserRepositoryInterface {
	/**
	 * Recup�re un utilisateur par son identifiant
	 *
	 * @param string $id
	 */
	public function getUserById($id);
	
	/**
	 * Recup�re un utilisateur par son compte utilisateur
	 *
	 * @param string $id
	 */
	public function getUserByUsername($username);
	
	/**
	 * Recup�re un utilisateur par son login et son mot de passe
	 *
	 * @param string $login
	 * @param string $password
	 */
	public function getUserByLoginAndPassword($login, $password);
	
	/**
	 * Ajoute une nouvel utilisateur
	 *
	 * @param \Application\Domain\Aggregate\User $user
	*/
	public function addUser(User $user);
	
	/**
	 * Modifie un utilisateur
	 *
	 * @param \Application\Domain\Aggregate\User $user
	*/
	public function modifyUser(User $user);
}