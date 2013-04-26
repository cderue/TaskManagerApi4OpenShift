<?php
/**
 * Task Manager
 *
 * @author C�dric Derue <cedric.derue@gmail.com>
 */

namespace Application\Domain\Contract;

use Application\Domain\Aggregate\Category;

interface CategoryRepositoryInterface {
	/**
	 * R�cup�re toutes les cat�gories d'un utilisateur
	 */
	public function getAllCategories($userId);
	
	/**
	 * Recup�re une cat�gorie par son identifiant
	 * 
	 * @param string $id
	 */
	public function getCategoryById($id);
	
	/**
	 * S�lectionne une cat�gorie par son nom
	 *
	 * @param string $name
	 */
	public function getCategoryByName($name);
	
	/**
	 * Ajoute une cat�gorie
	 * 
	 * @param Category $category
	 */
	public function addCategory(Category $category);
	
	/**
	 * Modifie une cat�gorie
	 * 
	 * @param Category $category
	 */
	public function modifyCategory(Category $category);
	
	/**
	 * Supprime une cat�gorie
	 * 
	 * @param Category $category
	 */
	public function removeCategory(Category $category);
}