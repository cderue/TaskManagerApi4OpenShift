<?php
/**
 * Task Manager
 *
 * @author Cédric Derue <cedric.derue@gmail.com>
 */

namespace Application\Domain\Contract;

use Application\Domain\Aggregate\Task;

interface TaskRepositoryInterface {
	/**
	 * Récupère toutes les tâches d'un utilisateur
	 */
	public function getAllTasks($userId);
	
	/**
	 * Recupère une tâche par son identifiant
	 * 
	 * @param string $id
	 */
	public function getTaskById($id);
	
	/**
	 * Ajoute une nouvelle tâche
	 * 
	 * @param Task $task
	 */
	public function addTask(Task $task);
	
	/**
	 * Modifie une tâche
	 * 
	 * @param Task $task
	 */
	public function modifyTask(Task $task);
	
	/**
	 * Supprime une tâche
	 * 
	 * @param Task $task
	 */
	public function removeTask(Task $task);
}