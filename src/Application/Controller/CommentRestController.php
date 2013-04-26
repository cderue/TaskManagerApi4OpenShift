<?php

namespace Application\Controller;

use ZendServerGateway\Controller\AbstractRestfulController;
use Application\Domain\Aggregate\Comment;
use Application\Domain\Contract\TaskRepositoryInterface;

class CommentRestController extends AbstractRestfulController {
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
	
	public function get($id) {
		$uri = $this->getRequest()->getUri();
		$path = $uri->getPath();
		$params = explode('/', $path);
		$taskId = $params[2];
		
		$task = $this->getTaskRepository()->getTaskById($taskId);
		if (null !== $task) {
			$comments = $task->getComments();
			foreach ($comments as $comment) {
				if ($comment->getId() === $id) {
					return $comment->toArray();
				}
			}
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		$this->getResponse()->setStatusCode(404);
	}
	
	public function getList() {
		$uri = $this->getRequest()->getUri();
		$path = $uri->getPath();
		$params = explode('/', $path);
		$taskId = $params[2];
		
		$task = $this->getTaskRepository()->getTaskById($taskId);
		if (null !== $task) {
			$comments = $task->getComments();
			$list = array();
			foreach ($comments as $comment) {
				$list[] = $comment->toArray();
			}
			return $list;
		}
			
		$this->getResponse()->setStatusCode(404);
	}
	
	public function create($data) {
		$uri = $this->getRequest()->getUri();
		$path = $uri->getPath();
		$params = explode('/', $path);
		$taskId = $params[2];
		
		$taskToUpdate = $this->getTaskRepository()->getTaskById($taskId);
		if (null !== $taskToUpdate) {
			$comment = new Comment();
			$comment->exchangeArray($data);
			$taskToUpdate->getComments()[] = $comment;
			if ($taskToUpdate->validate()) {
				$this->getTaskRepository()->modifyTask($taskToUpdate);
				$this->getResponse()->setStatusCode(201);
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
	
	public function update($id, $data) {
		$uri = $this->getRequest()->getUri();
		$path = $uri->getPath();
		$params = explode('/', $path);
		$taskId = $params[2];
		
		$taskToUpdate = $this->getTaskRepository()->getTaskById($taskId);
		if (null !== $taskToUpdate) {
			$comments = $taskToUpdate->getComments();
			foreach ($comments as $comment) {
				if ($id === $comment->getId()) {
					$data['id'] = $id;
					$comment->exchangeArray($data);
				}
			}
			if ($taskToUpdate->validate()) {
				$this->getTaskRepository()->modifyTask($taskToUpdate);
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
	
	public function delete($id) {
		$taskToUpdate = $this->getTaskRepository()->getTaskById($task_id);
		if (null !== $taskToUpdate) {
			$comments = $taskToUpdate->getComments();
			foreach ($comments as $key => $comment) {
				if ($id === $comment->getId()) {
					unset($comments[$key]);
				}
			}
			$this->getTaskRepository()->modifyTask($taskToUpdate);
			$this->getResponse()->setStatusCode(204);
		} else {
			$this->getResponse()->setStatusCode(422);
			$this->getResponse()
			->getHeaders()
			->addHeaderLine('Content-type', 'application/error+json');
		}
	}
}

?>