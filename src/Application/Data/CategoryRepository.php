<?php

namespace Application\Data;

use Application\Domain\Contract\CategoryRepositoryInterface;
use Application\Domain\Aggregate\Category;
use Doctrine\ODM\MongoDB\DocumentRepository;

class CategoryRepository extends DocumentRepository implements CategoryRepositoryInterface {
	/* (non-PHPdoc)
	 * @see \Data\Contract\CategoryRepositoryInterface::getAllCategories()
	*/
	public function getAllCategories($userId) 
	{
		return $this->getDocumentManager()
		            ->getRepository('Application\Domain\Aggregate\Category')
		            ->findBy(array('owner' => new \MongoId($userId)))->toArray();
	}
	
	/* (non-PHPdoc)
	 * @see \Data\Contract\CategoryRepositoryInterface::getCategoryByid()
	*/
	public function getCategoryById($id) {
		return $this->find($id);
	}
	
	/* (non-PHPdoc)
	 * @see \Application\Data\Contract\CategoryRepositoryInterface::getCategoryByName()
	*/
	public function getCategoryByName($name) {
		return $this->findOneBy(array('name' => $name));
	}
	
	/* (non-PHPdoc)
	 * @see \Data\Contract\CategoryRepositoryInterface::addCategory()
	 */
	public function addCategory(Category $category) {
		if (null !== $category) {
			$this->getDocumentManager()->persist($category);
			$this->getDocumentManager()->flush();
		} else {
			// Log
		}
	}
	
	/* (non-PHPdoc)
	 * @see \Data\Contract\CategoryRepositoryInterface::modifyCategory()
	*/
	public function modifyCategory(Category $category) {
		if (null !== $category) {
			$this->getDocumentManager()->persist($category);
			$this->getDocumentManager()->flush();
		} else {
			// Log
		}
	}
	
	/* (non-PHPdoc)
	 * @see \Data\Contract\CategoryRepositoryInterface::removeCetgory()
	*/
	public function removeCategory(Category $category) {
		if (null !== $category) {
			$this->getDocumentManager()->remove($category);
			$this->getDocumentManager()->flush();
		} else {
			// Log
		}
	}
}