<?php

namespace Application\Domain\Aggregate;

use Zend\Validator\AbstractValidator;

class PeriodValidator extends AbstractValidator {
	const INVALID = 'INVALID';
	const INVALID_PERIOD = 'INVALID_PERIOD';
	
	/* (non-PHPdoc)
	 * @see \Zend\Validator\ValidatorInterface::isValid()
	 */
	public function isValid($value) {
		if (!is_array($value) && !($value instanceof Period)) {
            $this->error(self::INVALID_VALUE);
            return false;
        }
        
        $beginning = '';
        $end = '';
        if ($value instanceof Period) {
            $beginning = $value->getBeginning();
            $end = $value->getEnd();
        } elseif (is_array($value)) {
            $beginning = $value['beginning'];
            $end = $value['end'];
        }
        if ($beginning > $end) {
            $this->error(self::INVALID_PERIOD);
            return false;
        }
        
        return true;
	}

}