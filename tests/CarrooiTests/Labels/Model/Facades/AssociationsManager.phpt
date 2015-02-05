<?php

/**
 * Test: Carrooi\Labels\Model\Facades\AssociationsManager
 *
 * @testCase CarrooiTests\Labels\Model\Facades\AssociationsManagerTest
 * @author David Kudera
 */

namespace CarrooiTests\Labels\Model\Facades;

use Carrooi\Labels\Model\Facades\AssociationsManager;
use CarrooiTests\Labels\TestCase;
use Nette\Object;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 *
 * @author David Kudera
 */
class AssociationsManagerTest extends TestCase
{


	public function testFunctionality()
	{
		$manager = new AssociationsManager;

		Assert::count(0, $manager->getAssociations());

		$manager->addAssociation('\Nette\Object', 'object');

		Assert::same('object', $manager->getAssociation('Nette\Object'));
		Assert::same('object', $manager->getAssociation('\Nette\Object'));

		Assert::same([
			'Nette\Object' => 'object',
		], $manager->getAssociations());

		Assert::true($manager->hasAssociation('Nette\Object'));
		Assert::true($manager->hasAssociation('\Nette\Object'));
	}


	public function testFunctionality_extendedClass()
	{
		$manager = new AssociationsManager;

		Assert::count(0, $manager->getAssociations());

		$manager->addAssociation('\Nette\Object', 'object');

		Assert::same('object', $manager->getAssociation('CarrooiTests\Labels\Model\Facades\SuperObject'));
		Assert::same('object', $manager->getAssociation('\CarrooiTests\Labels\Model\Facades\SuperObject'));

		Assert::same([
			'Nette\Object' => 'object',
			'CarrooiTests\Labels\Model\Facades\SuperObject' => 'object',
		], $manager->getAssociations());

		Assert::true($manager->hasAssociation('CarrooiTests\Labels\Model\Facades\SuperObject'));
		Assert::true($manager->hasAssociation('\CarrooiTests\Labels\Model\Facades\SuperObject'));
	}

}


/**
 *
 * @author David Kudera
 */
class SuperObject extends Object {}


run(new AssociationsManagerTest);
