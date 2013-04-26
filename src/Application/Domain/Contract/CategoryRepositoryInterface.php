<?php
/**
 * Task Manager
 *
 * @author Cédric Derue <cedric.derue@gmail.com>
 */

namespace Application\Domain\Contract;

use Application\Domain\Aggregate\Category;

interface CategoryRepositoryInterface {
	/**
	 * Récupère toutes les catégories d'un utilisateur
	 */
	public function getAllCategories($userId);
	
	/**
	 * Recupère une catégorie par son identifiant
	 * 
	 * @param string $id
	 */
	public function getCategoryById($id);
	
	/**
	 * Sélectionne une catégorie par son nom
	 *
	 * @param string $name
	 */
	public function getCategoryByName($name);
	
	/**
	 * Ajoute une catégorie
	 * 
	 * @param Category $category
	 */
	public function addCategory(Category $category);
	
	/**
	 * Modifie une catégorie
	 * 
	 * @param Category $category
	 */
	public function modifyCategory(Category $category);
	
	/**
	 * Supprime une catégorie
	 * 
	 * @param Category $category
	 */
	public function removeCategory(Category $category);
}