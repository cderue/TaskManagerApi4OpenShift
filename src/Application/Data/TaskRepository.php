<?php

namespace Application\Data;

use Application\Domain\Contract\TaskRepositoryInterface;
use Application\Domain\Aggregate\Task;
use Doctrine\ODM\MongoDB\DocumentRepository;

class TaskRepository extends DocumentRepository implements TaskRepositoryInterface {
	/* (non-PHPdoc)
	 * @see \Data\Contract\TaskRepositoryInterface::addTask()
	 */
	public function addTask(Task $task) {
		if (null !== $task) {
			$this->getDocumentManager()->persist($task);
			$this->getDocumentManager()->flush();
		} else {
			// Log
		}
	}

	/* (non-PHPdoc)
	 * @see \Data\Contract\TaskRepositoryInterface::getAllTasks()
	 */
	public function getAllTasks($userId) {
		return $this->getDocumentManager()
		            ->getRepository('Application\Domain\Aggregate\Task')
		            ->findBy(array('owner' => new \MongoId($userId)))->toArray();
	}

	/* (non-PHPdoc)
	 * @see \Data\Contract\TaskRepositoryInterface::getTaskById()
	 */
	public function getTaskById($id) {
		return $this->getDocumentManager()->getRepository('Application\Domain\Aggregate\Task')->find($id);
	}
	
	/* (non-PHPdoc)
	 * @see \Data\Contract\TaskRepositoryInterface::modifyTask()
	*/
	public function modifyTask(Task $task) {
		if (null !== $task) {
			$this->getDocumentManager()->persist($task);
			$this->getDocumentManager()->flush();
		} else {
			// Log
		}
	}
	
	/* (non-PHPdoc)
	 * @see \Data\Contract\TaskRepositoryInterface::removeTask()
	*/
	public function removeTask(Task $task) {
		if (null !== $task) {
			$this->getDocumentManager()->remove($task);
			$this->getDocumentManager()->flush();
		} else {
			// Log	
		}
	}
}