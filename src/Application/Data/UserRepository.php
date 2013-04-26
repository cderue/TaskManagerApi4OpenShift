<?php

namespace Application\Data;

use Application\Domain\Contract\UserRepositoryInterface;
use Application\Domain\Aggregate\User;
use Doctrine\ODM\MongoDB\DocumentRepository;

class UserRepository extends DocumentRepository implements UserRepositoryInterface {
	/* (non-PHPdoc)
	 * @see \Application\Domain\Contract\UserRepositoryInterface::addUser()
	 */
	public function addUser(User $user) {
		if (null !== $user) {
			$this->getDocumentManager()->persist($user);
			$this->getDocumentManager()->flush();
		} else {
			// Log
		}
	}

	/* (non-PHPdoc)
	 * @see \Application\Domain\Contract\UserRepositoryInterface::getUserById()
	 */
	public function getUserById($id) {
		return $this->getDocumentManager()->getRepository('Application\Domain\Aggregate\User')->find($id);
	}
	
	/* (non-PHPdoc)
	 * @see \Application\Domain\Contract\UserRepositoryInterface::getUserByLoginAndPassword()
	*/
	public function getUserByUsername($username) {
		$criteria = array('credential.username' => $username);
		return $this->getDocumentManager()->getRepository('Application\Domain\Aggregate\User')->findOneBy($criteria);
	}

	/* (non-PHPdoc)
	 * @see \Application\Domain\Contract\UserRepositoryInterface::getUserByLoginAndPassword()
	 */
	public function getUserByLoginAndPassword($login, $password) {
		$criteria = array('credential.username' => $login, 'credential.password' => $password);
		return $this->getDocumentManager()->getRepository('Application\Domain\Aggregate\User')->findOneBy($criteria);
	}

	/* (non-PHPdoc)
	 * @see \Application\Domain\Contract\UserRepositoryInterface::modifyUser()
	 */
	public function modifyUser(User $user) {
		if (null !== $user) {
			$this->getDocumentManager()->persist($user);
			$this->getDocumentManager()->flush();
		} else {
			// Log
		}
	}	
}