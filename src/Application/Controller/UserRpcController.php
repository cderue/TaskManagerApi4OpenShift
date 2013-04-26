<?php

namespace Application\Controller;

use ZendServerGateway\Controller\AbstractActionController;
use Application\Domain\Contract\UserRepositoryInterface;
use Application\Domain\Aggregate\User;

class UserRpcController extends AbstractActionController {
	/**
	 * Entrepôt des utilisateurs
	 *
	 * @var \Application\Domain\Contract\UserRepositoryInterface
	 */
	protected $userRepository;
	
	/**
	 * Retourne l'entrepôt des utilisateurs
	 *
	 * @return \Application\Domain\Contract\UserRepositoryInterface
	 */
	public function getUserRepository() {
		if (! $this->userRepository) {
			$serviceLocator = $this->getServiceLocator ();
			$this->userRepository = $serviceLocator->get ( 'Application\Data\UserRepository' );
		}
		return $this->userRepository;
	}
	
	/**
	 * Affecte l'entrepôt des utilisateurs
	 *
	 * @param \Application\Domain\Contract\UserRepositoryInterface $userRepository        	
	 */
	public function setUserRepository(UserRepositoryInterface $userRepository) {
		$this->userRepository = $userRepository;
	}
	public function postAuthenticateAction() {
		$username = $this->bodyParam('username');
		$password = md5($this->bodyParam('password'));
		
		$user = $this->getUserRepository ()->getUserByLoginAndPassword ( $username, $password );
		if (null !== $user) {
			$accessKey = $user->getKeys ()->getAccess ();
			$secretKey = $user->getKeys ()->getSecret ();
			$token = $this->generateToken ( $this->getRequest (), $accessKey, $secretKey );
			return array (
					'token' => $token 
			);
		}
		
		$this->getResponse ()->setStatusCode ( 422 );
		$this->getResponse ()->getHeaders ()->addHeaderLine ( 'Content-type', 'application/error+json' );
	}
	protected function generateToken($request, $accessKey, $secretKey) {
		$expires = (string)(time() + 24 * 60 * 60);
		
		// Calcule la signature
		$ipAddress = $request->getServer('REMOTE_ADDR');
		$userAgent = $request->getServer('HTTP_USER_AGENT');
		$hash = hash_hmac ( 'sha256', $accessKey . ':' . $ipAddress . ':' . $userAgent, $secretKey );
		
		// Construit le token
		$token = $accessKey . ':' . $hash . ':' . $expires;
		return $token;
	}
	
	public function postRegisterAction() {
		$user = $this->getUserRepository()->getUserByUsername($this->bodyParam('username'));
		if (null !== $user) {
			$this->getResponse()->setStatusCode(400);
			return "USER_ALREADY_EXISTS";
		}
		
		$data = array(
					'fullname' => $this->bodyParam('fullname'),
					'email' => $this->bodyParam('email'),
					'credential' => array(
										'username' => $this->bodyParam('username'),
										'password' => $this->bodyParam('password'),
									),
				);
		
		$user = new User();
		$user->exchangeArray($data);
		$user->autoGenerateKeys();
		
		if ($user->validate()) {
			$this->getUserRepository()->addUser($user);
			$newUser = $this->getUserRepository()->getUserByUsername($user->getCredential()->getUsername());
			if (null !== $newUser) {
				$this->getResponse()->setStatusCode(201);
				return $newUser->toArray();
			}
		} else {
			$this->getResponse()->setStatusCode(400);
			return $user->getErrors();
		}
		
		$this->getResponse()->setStatusCode(422);
		$this->getResponse()
		->getHeaders()
		->addHeaderLine('Content-type', 'application/error+json');
	}
}