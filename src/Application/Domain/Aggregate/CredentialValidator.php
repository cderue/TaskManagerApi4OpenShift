<?php

namespace Application\Domain\Aggregate;

use Zend\Validator\AbstractValidator;

class CredentialValidator extends AbstractValidator 
{
	const INVALID_VALUE    = 'INVALID_VALUE';
	const INVALID_USERNAME = 'INVALID_USERNAME';
	const INVALID_PASSwORD = 'INVALID_PASSWORD';
	
	/**
     * Valide un compte utilisateur
     */
	public function isValid($value) {
        if (!is_array($value) 
            && !($value instanceof Credential)) {
            $this->error(self::INVALID_VALUE);
            return false;
        }

        $username = '';
        $password = '';
        if ($value instanceof Credential) {
            $username = $value->getUsername();
            $password = $value->getPassword();
        } elseif (is_array($value)) {
            $username = $value['username'];
            $password = $value['password'];
        }
        if (empty($username)) {
            $this->error(self::INVALID_USERNAME);
            return false;
        }
        if (empty($password) || $username === $password) {
            $this->error(self::INVALID_PASSWORD);
            return false;
        }

        return true;
    }
}