<?php

namespace Application\Service;

use Zend\Http\PhpEnvironment\Request;

class AuthenticationService {
	
	public function validateToken($token) {
		$request = new Request();
		$uri = $request->getUriString();
		
		
		
		list($accessKeyId, $signature, $expires) = explode(':', $token, 3);
		
		// Refuse l'authentification si la date d'expiration
		// du token est d�pass�e
		$timestamp = time();
		if ($timestamp > $expires) {
			return false;
		}
		
		// Recup�re la cl� secr�te sinon l'authentification �choue
		$mongo = new \Mongo();
		$db = $mongo->taskmanager;
		$user = $db->users->findOne(array('keys.access' => $accessKeyId));
		if ($user) {
			$secretKey = $user['keys']['secret'];
		} else {
			return false;
		}
		
		// Calcule la signature
		$ipAddress = $request->getServer('REMOTE_ADDR');
		$userAgent = $request->getServer('HTTP_USER_AGENT');	
		$hash = hash_hmac('sha256', $accessKeyId . ':' . $ipAddress . ':' . $userAgent, $secretKey);
		
		// Compare la signature calcul�e avec celle du token
		if ($hash === $signature) {
			return true;
		}
		
		return false;	
	}
}

?>