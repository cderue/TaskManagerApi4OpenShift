<?php
/**
 * Task Manager
 *
 * @author C�dric Derue <cedric.derue@gmail.com>
 */

namespace Application\Domain\Contract;

use Application\Domain\Aggregate\Task;

interface TaskRepositoryInterface {
	/**
	 * R�cup�re toutes les t�ches d'un utilisateur
	 */
	public function getAllTasks($userId);
	
	/**
	 * Recup�re une t�che par son identifiant
	 * 
	 * @param string $id
	 */
	public function getTaskById($id);
	
	/**
	 * Ajoute une nouvelle t�che
	 * 
	 * @param Task $task
	 */
	public function addTask(Task $task);
	
	/**
	 * Modifie une t�che
	 * 
	 * @param Task $task
	 */
	public function modifyTask(Task $task);
	
	/**
	 * Supprime une t�che
	 * 
	 * @param Task $task
	 */
	public function removeTask(Task $task);
}