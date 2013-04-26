<?php
/**
 * Task Manager
 *
 * @author Cédric Derue <cedric.derue@gmail.com>
 */

namespace Application\Domain\Aggregate;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zend\Validator\NotEmpty;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\InArray;

/**
 * @ODM\Document(collection="tasks", repositoryClass="Application\Data\TaskRepository")
 */
class Task extends Entity implements ValidatableObjectInterface
{
	/**
	 * Statut de la tâche en attente
	 */
	const STATUS_IN_WAIT = 101;
	
	/**
	 * Statut de la tâche en progrès
	 */
	const STATUS_IN_PROGRESS = 102;
	
	/**
	 * Statut de la tâche terminé
	 */
	const STATUS_TERMINATED = 103;
	
	/**
	 * Priorité de la tâche basse
	 */
	const PRIORITY_LOW = 201;
	
	/**
	 * Priorité de la tâche modérée
	 */
	const PRIORITY_MEDIUM = 202;
	
	/**
	 * Priorité de la tâche haute
	 */
	const PRIORITY_HIGH = 203;
	
	/**
	 * Objet de la tâche
	 * 
	 * @var string
	 * @ODM\String
	 */
	protected $object;
	
	/**
	 * Période
	 *
	 * @var Period
	 * @ODM\EmbedOne(targetDocument="Application\Domain\Aggregate\Period")
	 */
	protected $period;
	
	/**
	 * Catégorie
	 * 
	 * @var Application\Domain\Aggregate\Category
	 * @ODM\ReferenceOne(targetDocument="Application\Domain\Aggregate\Category")
	 */
	protected $category;

	/**
	 * Statut de la tâche
	 * 
	 * @var string
	 * @ODM\Int
	 */
	protected $status = 0;

	/**
	 * Niveau de priorité
	 * 
	 * @var int
	 * @ODM\Int
	 */
	protected $priority = 0;
	
	/**
	 * Commentaires
	 * 
	 * @var array
	 * @ODM\EmbedMany(targetDocument="Application\Domain\Aggregate\Comment")
	 */
	protected $comments = array();
	
	/**
	 * Propriétaire
	 * 
	 * @var string
	 * @ODM\ReferenceOne(targetDocument="Application\Domain\Aggregate\User", simple=true)
	 */
	protected $owner;
	
	/**
	 * Erreurs de validation
	 * 
	 * @var array
	 */
	protected $errors;

	/**
	 * Retourne l'objet de la tâche
	 * 
	 * @return string
	 */
	public function getObject() {
		return $this->object;
	}

	/**
	 * Affecte l'objet de la tâche
	 * 
	 * @param string $object
	 */
	public function setObject($object) {
		$this->object = $object;
		return $this;
	}
	
	/**
	 * Retourne la période
	 *
	 * @return Period
	 */
	public function getPeriod() {
		/*if (null === $this->period) {
		 $this->period = new Period();
		}*/
		return $this->period;
	}
	
	/**
	 * Affecte la période
	 *
	 * @param Period $period
	 */
	public function setPeriod(Period $period) {
		$this->period = $period;
		return $this;
	}
	
	/**
	 * Retourne la catégorie
	 * 
	 * @return \Application\Domain\Aggregate\Category
	 */
	public function getCategory() {
		return $this->category;
	}
	
	/**
	 * Affecte la catégorie
	 * 
	 * @param Application\Domain\Aggregate\Category $category
	 */
	public function setCategory(Category $category) {
		$this->category = $category;
	}
	
	/**
	 * Retourne le statut de la tâche
	 * 
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Affecte le statut de la tâche
	 * 
	 * @param string $status
	 */
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}

	/**
	 * Retourne le niveau de priorité
	 * 
	 * @return the $priority
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * Affecte le niveau de priorité
	 * 
	 * @param int $priority
	 */
	public function setPriority($priority) {
		$this->priority = $priority;
		return $this;
	}
	
	/**
	 * Retourne les commentaires
	 * 
	 * @return array
	 */
	public function getComments() {
		return $this->comments;
	}

	/**
	 * Affecte les commentaires
	 * 
	 * @param array $comments
	 */
	public function setComments(array $comments) {
		$this->comments = $comments;
	}
	
	public function addComment()
	{
		
	}
	
	public function setComment()
	{
	
	}
	
	public function removeComment()
	{
	
	}
	
	/**
	 * Retourne le propriétaire
	 *
	 * @return string
	 *
	 */
	public function getOwner() {
		return $this->owner;
	}
	
	/**
	 * Affecte le propriétaire
	 *
	 * @param \Application\Domain\Aggregate\User $owner
	 */
	public function setOwner(User $owner) {
		$this->owner = $owner;
		return $this;
	}
	
	
	/**
	 * Initialise l'objet depuis un tableau
	 *
	 * @param array $data
	 */
	public function exchangeArray(array $data)
	{
		$this->id     = (isset($data['id'])) ? $data['id'] : null;
		$this->object = (isset($data['object'])) ? $data['object'] : null;
		if (isset($data['period']) && $data['period'] instanceof Period) {
			$this->period = $data['period'];
		} elseif (isset($data['period']) && is_array($data['period'])) {
			$beginning 		= (isset($data['period']['beginning'])) ? new \DateTime($data['period']['beginning']) : null;
			$end	 	  	= (isset($data['period']['end'])) ? new \DateTime($data['period']['end']) : null;
			$this->period 	= new Period($beginning, $end);
		} else {
			$this->period = null;
		}
		$this->status 	= (isset($data['status'])) ? $data['status'] : 0;
		$this->priority = (isset($data['priority'])) ? $data['priority'] : 0;
		$comments = (isset($data['comments']))? $data['comments']: array();
		foreach ($comments as $comment) {
			if ($comment instanceof Comment) {
				$this->comments[] = $comment;
			} elseif (is_array($comment)) {
				$id = (isset($data['id']))? $data['id']: null;
				$content = (isset($data['content']))?
				$data['content']: null;
				$created = (isset($data['created']))?
				$data['created']: null;
				$newComment = new Comment();
				$newComment->setId($id);
				$newComment->setContent($content);
				$newComment->setCreated($created);
				$this->comments[] = $newComment;
			}
		}
		
	}
	
	/**
	 * Convertit l'objet courant en tableau
	 *
	 * @return array
	 */
	public function toArray()
	{
		$arr = $this->getComments();
		$comments = array();
		foreach ($arr as $comment) {
			$comments[] = $comment->toArray();
		}
		return array(
				'id'       => $this->getId(),
				'object'   => $this->getObject(),
				'period'   => (null !== $this->getPeriod()) ? $this->getPeriod()->toArray() : null,
				'category' => (null !== $this->getCategory())?$this->getCategory()->toArray() : null,
				'status'   => $this->getStatus(),
				'priority' => $this->getPriority(),
				'comments' => $comments,
				'owner'    => $this->getOwner(),
		);
	}

	/**
	 * Valide les données de l'objet
	 * 
	 * @return boolean
	 */
	public function validate() {
		$this->errors = array();
		$data = $this->toArray();
		
		$authorizedStatus = array(
				self::STATUS_IN_WAIT => 0,
				self::STATUS_IN_PROGRESS => 1,
				self::STATUS_TERMINATED => 2,
		);

		$authorizedPriorities = array(
				self::PRIORITY_LOW => 0,
				self::PRIORITY_MEDIUM => 1,
				self::PRIORITY_HIGH => 2,
		);
		
		$inputFilter = new InputFilter();
		
		$objectInput = new Input('object');
		$objectInput->getFilterChain()
			  		 ->attachByName('stringtrim')
			  		 ->attachByName('striptags');
		$objectInput->getValidatorChain()
					 ->addValidator(new NotEmpty());
		
		$periodInput = new Input('period');
		$periodInput->getValidatorChain()
					->addValidator(new PeriodValidator());
		
		$statusInput = new Input('status');
		$statusInput->getValidatorChain()
					->addValidator(new InArray(array('haystack' => $authorizedStatus)));
		
		$priorityInput = new Input('priority');
		$priorityInput->getValidatorChain()
					  ->addValidator(new InArray(array('haystack' => $authorizedPriorities)));
		
		$inputFilter->add($objectInput)
					->add($periodInput)
					->add($statusInput)
					->add($priorityInput);
		
		$inputFilter->setData($data);
		return $inputFilter->isValid();
	}
	
	/**
	 * Retourne les erreurs de validation
	 * 
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}
}