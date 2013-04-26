<?php

use Application\Domain\Aggregate\Task;
use Application\Domain\Aggregate\Period;

/**
 * Task test case.
 */
class TaskTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * 
	 */
	public function testTaskInitialState()
	{
		$task = new Task();
	
		$this->assertNull($task->getId(), '"id" should initially be null');
		$this->assertNull($task->getObject(), '"name" should initially be null');
		$this->assertNull($task->getPeriod(), '"period" should initially be null');
		$this->assertEquals(0, $task->getStatus(), '"status" should initially be equals to 0');
		$this->assertEquals(0, $task->getPriority(), '"priority" should initially be equals to 0');
		$this->assertEquals(array(), $task->getComments(), '"priority" should initially be equals to 0');
	}
	
	/**
	 * 
	 */
	public function testExchangeArraySetsPropertiesCorrectly()
	{
		$task = new Task();
		$data =array(
			'id' 	   => '512f438bfc45c3b40c000000',
			'object'   => 'Passer au pressing',
			'period'   => new Period(),
			'status'   => 0,
			'priority' => 0,
			'comments' => array(),
		);
		$task->exchangeArray($data);
	
		$this->assertSame($data['id'], $task->getId(), '"id" was not set correctly');
		$this->assertSame($data['object'], $task->getObject(), '"object" was not set correctly');
		$this->assertSame($data['period'], $task->getPeriod(), '"period" was not set correctly');
		$this->assertSame($data['status'], $task->getStatus(), '"status" was not set correctly');
		$this->assertSame($data['priority'], $task->getPriority(), '"priority" was not set correctly');
		$this->assertSame($data['comments'], $task->getComments(), '"comments" was not set correctly');
	}
	
	/**
	 * 
	 */
	public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
	{
		$task = new Task();
		$task->exchangeArray(array());
	
		$this->assertNull($task->getId(), '"id" should have defaulted to null');
		$this->assertNull($task->getObject(), '"object" should have defaulted to null');
		$this->assertNull($task->getPeriod(), '"period" should have defaulted to null');
		$this->assertSame(0, $task->getStatus(), '"status" should have defaulted to 0');
		$this->assertSame(0, $task->getPriority(), '"priority" should have defaulted to 0');
		$this->assertSame(array(), $task->getComments(), '"comments" should have defaulted to array');
	}
	
	/**
	 * 
	 */
	public function testToArrayReturnsAnArrayWithPropertyValues()
	{
		$task = new Task();
		$data =array(
			'id' 	   => '512f438bfc45c3b40c000000',
			'object'   => 'Passer au pressing',
			'period'   => array(
							'beginning' => '2013-03-01',
							'end' => '2013-03-28',
							),
			'status'   => 0,
			'priority' => 0,
			'comments' => array(),
		);
		$task->exchangeArray($data);
		$copyArray = $task->toArray();
	
		$this->assertSame($data['id'], $copyArray['id'], '"id" was not set correctly');
		$this->assertSame($data['object'], $copyArray['object'], '"object" was not set correctly');
		$this->assertSame($data['period'], $copyArray['period'], '"period" was not set correctly');
		$this->assertSame($data['status'], $copyArray['status'], '"status" was not set correctly');
		$this->assertSame($data['priority'], $copyArray['priority'], '"priority" was not set correctly');
		$this->assertSame($data['comments'], $copyArray['comments'], '"comments" was not set correctly');
	}
	
	/**
	 * 
	 */
	public function testSameIdentityProducesAnEquality()
	{
		$leftTask = new Task();
		$leftData =array(
			'id' 	   => '512f438bfc45c3b40c000000',
			'object'   => 'Passer au pressing',
			'period'   => new Period(),
			'status'   => 0,
			'priority' => 0,
			'comments' => array(),
		);
		$leftTask->exchangeArray($leftData);
		
		$rightTask = new Task();
		$rightData =array(
			'id' 	   => '512f438bfc45c3b40c000000',
			'object'   => 'Passer au pressing',
			'period'   => new Period(),
			'status'   => 0,
			'priority' => 0,
			'comments' => array(),
		);
		$rightTask->exchangeArray($rightData);
		
		$this->assertTrue($leftTask->equals($rightTask, "'equality' was expected"));
	}
	
	/**
	 * 
	 */
	public function testDifferentIdentityProducesAnInequality()
	{
		$leftTask = new Task();
		$leftData =array(
			'id' 	   => '512f438bfc45c3b40c000000',
			'object'   => 'Passer au pressing',
			'period'   => new Period(),
			'status'   => 0,
			'priority' => 0,
			'comments' => array(),
		);
		$leftTask->exchangeArray($leftData);
	
		$rightTask = new Task();
		$rightData =array(
			'id' 	   => '512f438bfc45c3b40c000000',
			'object'   => 'Passer au pressing',
			'period'   => new Period(),
			'status'   => 0,
			'priority' => 0,
			'comments' => array(),
		);
		$rightTask->exchangeArray($rightData);
	
		$this->assertTrue($leftTask->equals($rightTask, "'inequality' was expected"));
	}
	
	/**
	 * 
	 */
	public function testSetIdentityProducesNonTransientEntity()
	{
		$task = new Task();
		$data =array(
			'id' 	   => '512f438bfc45c3b40c000000',
			'object'   => 'Passer au pressing',
			'period'   => new Period(),
			'status'   => 0,
			'priority' => 0,
			'comments' => array(),
		);
		$task->exchangeArray($data);
		
		$this->assertFalse($task->isTransient(), "non transient entity was expected");
	}
	
	/**
	 * 
	 */
	public function testSetNullIdentityProducesTransientEntity()
	{
		$task = new Task();
		$data =array(
			'id' 	   => null,
			'object'   => 'Passer au pressing',
			'period'   => new Period(),
			'status'   => 0,
			'priority' => 0,
			'comments' => array(),
		);
		$task->exchangeArray($data);
		
		$this->assertTrue($task->isTransient(), "transient entity was expected");
	}
	
	/**
	 * 
	 */
	public function testValidateReturnsTrueIfDataAreValid()
	{
		$task = new Task();
		$data =array(
				'object'   => 'Passer au pressing',
				'period'   => new Period(),
				'status'   => 0,
				'priority' => 0,
				'comments' => array(),
		);
		$task->exchangeArray($data);
		
		$this->assertTrue($task->validate(), 'Valid state was expected');
	}
	
	/**
	 * 
	 */
	public function testValidateReturnsFalseIfObjectIsInvalid()
	{
		$task = new Task();
		$data =array(
				'object'   => '',
				'period'   => new Period(),
				'status'   => 0,
				'priority' => 0,
				'comments' => array(),
		);
		$task->exchangeArray($data);
		
		$this->assertFalse($task->validate(), 'Invalid state was expected');
	}
	
	/**
	 * 
	 */
	public function testValidateReturnsFalseIfStatusIsInvalid()
	{
		$task = new Task();
		$data =array(
				'object'   => '',
				'period'   => new Period(),
				'status'   => 4,
				'priority' => 0,
				'comments' => array(),
		);
		$task->exchangeArray($data);
	
		$this->assertFalse($task->validate(), 'Invalid state was expected');
	}
}