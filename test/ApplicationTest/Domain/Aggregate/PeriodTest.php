<?php

use Application\Domain\Aggregate\Period;

/**
 * Period test case.
 */
class PeriodTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * 
	 */
	public function testPeriodDefaultInitialState()
	{	
		$period = new Period();
		$this->assertNull($period->getBeginning(), '"beginning" should initially be null');
		$this->assertNull($period->getEnd(), '"end" should initially be null');
	}
	
	/**
	 * 
	 */
	public function testPeriodInitialState()
	{
		$beginning = new \DateTime();
		$beginning->setDate(2013, 03, 01);
		$end = new \DateTime();
		$end->setDate(2013, 03, 28);
		$period = new Period($beginning, $end);
	
		$this->assertSame($beginning, $period->getBeginning(), '"beginning" was not set correctly');
		$this->assertSame($end, $period->getEnd(), '"end" was not set correctly');
	}
	
	/**
	 * 
	 */
	public function testEqualsReturnTrueIfDataAreTheSame()
	{
		$beginning = new \DateTime();
		$beginning->setDate(2013, 03, 01);
		$end = new \DateTime();
		$end->setDate(2013, 03, 28);
		$leftPeriod = new Period($beginning, $end);
		$rigthPeriod = new Period($beginning, $end);
	
		$this->assertTrue($leftPeriod->equals($rigthPeriod), 'equality was expected');
	}
	
	/**
	 * 
	 */
	public function testEqualsReturnFalseIfDataAreNotTheSame()
	{
		$beginning = new \DateTime();
		$beginning->setDate(2013, 03, 01);
		$end = new \DateTime();
		$end->setDate(2013, 03, 28);
		$leftPeriod = new Period($beginning, $end);
		$rigthPeriod = new Period();
		
		$this->assertFalse($leftPeriod->equals($rigthPeriod), 'Inequality was expected');
	}	
}