<?php

namespace Application\Controller;

use ZendServerGateway\Controller\AbstractActionController;

class RPCServices extends AbstractActionController {
	public function postAuthenticateAction() {
		$username = $this->bodyParam ( 'username' );
		$password = $this->bodyParam ( 'password' );
		return array ();
	}
}

?>