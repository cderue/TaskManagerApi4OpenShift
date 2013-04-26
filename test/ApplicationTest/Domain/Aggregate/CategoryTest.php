<?php

use Application\Domain\Aggregate\Category;
/**
 * Category test case.
 */
class CategoryTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * 
	 */
	public function testCategoryInitialState()
	{
		$category = new Category();
	
		$this->assertNull($category->getId(), '"id" should initially be null');
		$this->assertNull($category->getName(), '"name" should initially be null');
		$this->assertNull($category->getDescription(), '"description" should initially be null');
	}
	
	/**
	 * 
	 */
	public function testExchangeArraySetsPropertiesCorrectly()
	{
		$category = new Category();
		$data =array(
			'id' 		  => '512f438bfc45c3b40c000000',
			'name' 		  => 'Travail',
			'description' => 'Tâches à faire au bureau',
		);
		$category->exchangeArray($data);
	
		$this->assertSame($data['id'], $category->getId(), '"id" was not set correctly');
		$this->assertSame($data['name'], $category->getName(), '"name" was not set correctly');
		$this->assertSame($data['description'], $category->getDescription(), '"description" was not set correctly');
	}
	
	/**
	 * 
	 */
	public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
	{
		$category = new Category();
		$category->exchangeArray(array());
	
		$this->assertNull($category->getId(), '"id" should have defaulted to null');
		$this->assertNull($category->getName(), '"name" should have defaulted to null');
		$this->assertNull($category->getDescription(), '"description" should have defaulted to null');
	}
	
	/**
	 * 
	 */
	public function testToArrayReturnsAnArrayWithPropertyValues()
	{
		$category = new Category();
		$data =array(
			'id' 		  => '512f438bfc45c3b40c000000',
			'name' 		  => 'Travail',
			'description' => 'Tâches à faire au bureau',
		);
		$category->exchangeArray($data);
		$copyArray = $category->toArray();
	
		$this->assertSame($data['id'], $copyArray['id'], '"id" was not set correctly');
		$this->assertSame($data['name'], $copyArray['name'], '"name" was not set correctly');
		$this->assertSame($data['description'], $copyArray['description'], '"description" was not set correctly');
	}
	
	/**
	 * 
	 */
	public function testSameIdentityProducesAnEquality()
	{
		$leftCategory = new Category();
		$data =array(
				'id' 		  => '512f438bfc45c3b40c000000',
				'name' 		  => 'Travail',
				'description' => 'Tâches à faire au bureau',
		);
		
		$rightCategory = new Category();
		$data =array(
				'id' 		  => '512f438bfc45c3b40c000000',
				'name' 		  => 'Maison',
				'description' => 'Tâches à faire à la maison',
		);
		
		$this->assertTrue($leftCategory->equals($rightCategory, "'equality' was expected"));
	}
	
	/**
	 * 
	 */
	public function testDifferentIdentityProducesAnInequality()
	{
		$leftCategory = new Category();
		$data =array(
				'id' 		  => '512f438bfc45c3b40c000000',
				'name' 		  => 'Travail',
				'description' => 'Tâches à faire au bureau',
		);
	
		$rightCategory = new Category();
		$data =array(
				'id' 		  => '512f3879fc45c31404000000',
				'name' 		  => 'Maison',
				'description' => 'Tâches à faire à la maison',
		);
	
		$this->assertTrue($leftCategory->equals($rightCategory, "equality was expected"));
	}
	
	/**
	 * 
	 */
	public function testSetIdentityProducesNonTransientEntity()
	{
		$category = new Category();
		$data =array(
				'id' 		  => '512f438bfc45c3b40c000000',
				'name' 		  => 'Travail',
				'description' => 'Tâches à faire au bureau',
		);
		$category->exchangeArray($data);
		
		$this->assertFalse($category->isTransient(), "non transient entity was expected");
	}
	
	/**
	 * 
	 */
	public function testSetNullIdentityProducesTransientEntity()
	{
		$category = new Category();
		$data =array(
				'id' 		  => null,
				'name' 		  => 'Travail',
				'description' => 'Tâches à faire au bureau',
		);
		$category->exchangeArray($data);
		
		$this->assertTrue($category->isTransient(), "transient entity was expected");
	}
}

