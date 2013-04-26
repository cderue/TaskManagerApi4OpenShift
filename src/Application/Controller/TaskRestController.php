<?php

namespace Application\Controller;

use ZendServerGateway\Controller\AbstractRestfulController;
use Application\Domain\Aggregate\Task;
use Application\Domain\Contract\TaskRepositoryInterface;

class TaskRestController extends AbstractRestfulController  {
	/**
	 * Entrepôt des tâches
	 *
	 * @var TaskRepositoryInterface
	 */
	protected $taskRepository;
	
	/**
	 * Retourne l'entrepôt des tâches
	 * 
	 * @return TaskRepositoryInterface
	 */
	public function getTaskRepository() {
		if (!$this->taskRepository) {
			$serviceLocator = $this->getServiceLocator();
			$this->taskRepository = $serviceLocator->get('Application\Data\TaskRepository');
		}
		return $this->taskRepository;
	}
	
	/**
	 * Affecte l'entrepôt des tâches
	 * 
	 * @param TaskRepositoryInterface $taskRepository
	 */
	public function setTaskRepository(TaskRepositoryInterface $taskRepository) {
		$this->taskRepository = $taskRepository;
	}
	
	/* (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractRestfulController::create()
	 */
	public function create($data) {
		$task = new Task();
		$task->exchangeArray($data);
		
		if ($task->validate()) {
			$this->getTaskRepository()->addTask($task);
			$newTask = $this->getTaskRepository()->getTaskByName($task->getName());
			if (null !== $newTask) {
				$this->getResponse()->setStatusCode(201);
				return $newTask->toArray();
			}
		} else {
			$this->getResponse()->setStatusCode(400);
			return $task->getErrors();
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
		$taskToDelete = $this->getTaskRepository()->getTaskById($id);
		
		if (null !== $taskToDelete) {
			$this->getTaskRepository()->removeTask($taskToDelete);
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
		$task = $this->getTaskRepository()->getTaskById($id);
		if (null === $task) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		return $task->toArray();
	}

	/* (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractRestfulController::getList()
	 */
	public function getList() {
		$tasks = $this->getTaskRepository()->getAllTasks();
		$list = array();
		if (null !== $tasks) {
			foreach ($tasks as $task) {
				$list[] = $task->toArray();
			}
		}
		
		return $tasks;
	}

	/* (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractRestfulController::update()
	 */
	public function update($id, $data) {
		$taskToUpdate = $this->getTaskRepository()->getTaskById($id);
		
		if (null !== $taskToUpdate) {
			$data['id'] = $id;
			$taskToUpdate->exchangeArray($data);
			if ($taskToUpdate->validate()) {
				$this->getTaskRepository()->updateTask($taskToUpdate);
				return $taskToUpdate->toArray();
			} else {
				$this->getResponse()->setStatusCode(400);
				return $taskToUpdate->getErrors();
			}
		}
		
        $this->getResponse()->setStatusCode(422);
        $this->getResponse()
             ->getHeaders()
             ->addHeaderLine('Content-type', 'application/error+json');	
	}
}