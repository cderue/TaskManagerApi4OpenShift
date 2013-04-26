<?php

namespace Application\Controller;

use ZendServerGateway\Controller\AbstractRestfulController;
use Application\Domain\Aggregate\Category;
use Application\Domain\Contract\CategoryRepositoryInterface;

class CategoryRestController extends AbstractRestfulController {
	/**
	 * Entrepôt des catégories
	 * 
	 * @var CategoryRepositoryInterface
	 */
	protected $categoryRepository;
	
	/**
	 * Retourne l'entrepôt des catégories
	 * 
	 * @return CategoryRepositoryInterface
	 */
	public function getCategoryRepository() {
		if (!$this->categoryRepository) {
			$serviceLocator = $this->getServiceLocator();
			$this->categoryRepository = $serviceLocator->get('Application\Data\CategoryRepository');
		}
		return $this->categoryRepository;
	}
	
	/**
	 * Affecte l'entrepôt des catégories
	 * 
	 * @param CategoryRepositoryInterface $categoryRepository
	 */
	public function setCategoryRepository(CategoryRepositoryInterface $categoryRepository) {
		$this->categoryRepository = $categoryRepository;
	}
	
	/* (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractRestfulController::create()
	 */
	public function create($data) {
		$category = new Category();
		$category->exchangeArray($data);
		
		if ($category->validate()) {
			$this->getCategoryRepository()->addCategory($category);
			$newCategory = $this->getCategoryRepository()->getCategoryByName($category->getName());
			if (null !== $newCategory) {
				$this->getResponse()->setStatusCode(201);
				return $newCategory->toArray();
			}
		} else {
			$this->getResponse()->setStatusCode(400);
			return $category->getErrors();
		}
		
		$this->getResponse()->setStatusCode(422);
		$this->getResponse()
			 ->getHeaders()
			 ->addHeaderLine('Content-type', 'application/error+json');
	}

	/* (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractRestfulController::delete()
	 */
	public function delete($id) {
		$categoryToDelete = $this->getCategoryRepository()->getCategoryById($id);
		
		if (null !== $categoryToDelete) {
			$this->getCategoryRepository()->removeCategory($categoryToDelete);
			$this->getResponse()->setStatusCode(204);
		} else {
			$this->getResponse()->setStatusCode(422);
            $this->getResponse()
                 ->getHeaders()
                 ->addHeaderLine('Content-type', 'application/error+json');
		}
	}

	/* (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractRestfulController::get()
	 */
	public function get($id) {
		$category = $this->getCategoryRepository()->getCategoryById($id);
		if (null === $category) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		return $category->toArray();
	}

	/* (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractRestfulController::getList()
	 */
	public function getList() {
		$categories = $this->getCategoryRepository()->getAllCategories();
		$list = array();
		if (null !== $categories) {
			foreach ($categories as $category) {
				$list[] = $category->toArray();
			}
		}
		
		return $list;
	}

	/* (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractRestfulController::update()
	 */
	public function update($id, $data) {
		$categoryToUpdate = $this->getCategoryRepository()->getCategoryById($id);
		
		if (null !== $categoryToUpdate) {
			$data['id'] = $id;
			$categoryToUpdate->exchangeArray($data);
			if ($categoryToUpdate->validate()) {
				$this->getCategoryRepository()->modifyCategory($categoryToUpdate);
				return $categoryToUpdate->toArray();
			} else {
				$this->getResponse()->setStatusCode(400);
				return $categoryToUpdate->getErrors();
			}
		}
        
		$this->getResponse()->setStatusCode(422);
        $this->getResponse()
             ->getHeaders()
             ->addHeaderLine('Content-type', 'application/error+json');
	}
}